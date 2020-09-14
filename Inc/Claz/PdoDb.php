<?php

namespace Inc\Claz;

use Exception;
use PDO;
use PDOException;

/**
 * PdoDb class
 * This class is designed to work with forms with fields named
 * using the name of the field in the <b>mysql</b> table being
 * processed.
 * <b>post</b> method.
 * @author Rich Rowley
 */
class PdoDb
{
    private array $caseStmts;
    private bool $debug;
    private string $debugLabel;
    private int $debugMicroTime;
    private bool $distinct;
    private array $excludedFields;
    private array $fauxPost;
    private string $fieldPrefix;
    private array $functions;
    private array $groupBy;
    private Havings $havings;
    private array $joinStmts;
    private array $keyPairs;
    private string $lastCommand;
    private int $limit;
    private bool $noErrorLog;
    private string $onDuplicateKey;
    private Orderby $orderBy;
    private PDO $pdoDb;
    private PDO $pdoDb2;
    private bool $saveLastCommand;
    private bool $selectAll;
    private array $selectList;
    private array $selectStmts;
    private array $tableColumns;
    private array $tableConstraints;
    private string $tableEngine;
    private string $tableSchema;
    private bool $transaction;
    private bool $usePost;
    private WhereClause $whereClause;

    /**
     * Establish the PDO connector to the database.
     * Note that the global values, <b>$host</b>, <b>$database</b>, <b>$admin</b> and <b>$password</b>
     * must be set prior to instantiating this class.
     * @param DbInfo $dbInfo Object with database access information.
     * @param bool $debug Set to <b>true</b> to have the debug information
     *        written to the <i>error.log</i>.
     * @throws PdoDbException if a database error occurs.
     */
    public function __construct(DbInfo $dbInfo, bool $debug = false)
    {
        $this->clearAll();

        $this->tableSchema = $dbInfo->getDbname();
        $this->debug = $debug;
        $this->transaction = false;
        $host = $dbInfo->getHost() == "localhost" ? "127.0.0.1" : $dbInfo->getHost();
        $dsn = "mysql:dbname={$dbInfo->getDbname()};host=$host;port={$dbInfo->getPort()}";
        $username = $dbInfo->getUsername();
        $password = $dbInfo->getPassword();
        try {
            // Used for user requests.
            $this->pdoDb = new PDO($dsn, $username, $password);

            // Used internally to perform table structure look ups, etc. so these
            // queries will not impact in process activity for the user's requests.
            $this->pdoDb2 = new PDO($dsn, $username, $password);
        } catch (PDOException $exp) {
            $str = "PdoDb - construct error: " . $exp->getMessage();
            error_log($str);
            error_log("dbInfo - " . print_r($dbInfo, true));
            error_log(print_r($exp, true));
            throw new PdoDbException($str);
        }
    }

    /**
     * Class destructor
     * Verifies no incomplete transactions in process. If found, rollback performed.
     * @throws PdoDbException
     */
    public function __destruct()
    {
        if ($this->transaction) {
            error_log("PdoDb destruct - incomplete transaction - rollback performed.");
            $this->rollback();
        }
    }

    /**
     * Reset class properties with the exception of the database object,
     * to their default (unset) state.
     * @param bool $clearTran
     */
    public function clearAll(bool $clearTran = true): void
    {
        // @formatter:off
        $this->caseStmts        = [];
        $this->debugLabel       = "";
        $this->debugMicroTime   = 0;
        $this->distinct         = false;
        $this->excludedFields   = [];
        $this->fauxPost         = [];
        $this->fieldPrefix      = "";
        $this->functions        = [];
        $this->groupBy          = [];
        $this->havings          = new Havings();
        $this->joinStmts        = [];
        $this->keyPairs         = [];
        $this->lastCommand      = "";
        $this->limit            = 0;
        $this->noErrorLog       = false;
        $this->onDuplicateKey   = "";
        $this->orderBy          = new OrderBy();
        $this->saveLastCommand  = false;
        $this->selectAll        = false;
        $this->selectList       = [];
        $this->selectStmts      = [];
        $this->tableColumns     = [];
        $this->tableConstraints = [];
        $this->tableEngine      = "";
        $this->usePost          = true;
        $this->whereClause      = new WhereClause();
        if ($clearTran && isset($this->transaction) && $this->transaction) {
            try {
                $this->rollback();
            } catch (PdoDbException $pde) {
                error_log("PdoDb::clearAll() - Error: " . $pde->getMessage());
            }
        }
        // @formatter:on
    }

    /**
     * Turn on debug mode.
     * Enables error log display of query requests.
     * @param string $debugLabel Label to identify where the debug was performed.
     */
    public function debugOn(string $debugLabel = ""): void
    {
        $this->debug = true;
        if (empty($debugLabel)) {
            $bt = debug_backtrace();
            $idx = 1;
            while ($idx < count($bt)) {
                $debugLabel = $bt[$idx]['function'];
                if ($debugLabel != "debugOn") {
                    break;
                }
                $idx++;
            }
        }
        $this->debugLabel = $debugLabel;
    }

    /**
     * Turn off debug mode.
     */
    public function debugOff(): void
    {
        $this->debug = false;
    }

    /**
     * Sets flag to save the last command.
     * Note: Flag reset automatically after each command.
     */
    public function saveLastCommand(): void
    {
        $this->saveLastCommand = true;
    }

    /**
     * Returns the last command saved.
     * Note that this is reset when the next request is performed.
     * @return string Last command saved.
     */
    public function getLastCommand(): string
    {
        return $this->lastCommand;
    }

    /**
     * Make a token name for the current <i>PdoDb</i> request.
     * @param string $token Name of the field
     * @param int $cnt Counter used to make the unique token name.
     *        Note this parameter is <i>passed by reference</i> so it's updated
     *        value will be returned in it.
     * @return string Unique token name.
     */
    public static function makeToken(string $token, int &$cnt): string
    {
        $token = preg_replace('/\./', '_', $token);
        return sprintf("%s_%03d", $token, $cnt++);
    }

    /**
     * Set the <b>DISTINCT</b> attribute for selection
     * Note: If the request performed is not a <b>SELECT</b>, this
     * setting will be ignored.
     */
    public function setDistinct(): void
    {
        $this->distinct = true;
    }

    /**
     * Add a simple WhereItem testing for equality.
     * @param string $column Table column name.
     * @param string $value Value to test for.
     * @param string $connector (Optional) If specified, used to connect to a subsequent
     *        <i>WhereItem</i>. Typically "AND" or "OR".
     * @throws PdoDbException
     */
    public function addSimpleWhere(string $column, string $value, string $connector = ""): void
    {
        $this->addToWhere(new WhereItem(false, $column, "=", $value, false, $connector));
    }

    /**
     * Add and entry for a table column to correct.
     * @param string $column Name of table field.
     * @param string $type Data type of the table column, ex: VARCHAR(255)
     * @param string $attributes Additional column attributes,
     *        ex: NOT null AUTO_INCREMENT
     */
    public function addTableColumns(string $column, string $type, string $attributes): void
    {
        $structure = $type . " " . $attributes;
        $this->tableColumns[$column] = $structure;
    }

    /**
     * Specify the table engine
     * @param string $engine "InnoDb" or "MYISAM"
     */
    public function addTableEngine(string $engine): void
    {
        $this->tableEngine = $engine;
    }

    /**
     * Specify <b>ALTER TABLE</b> constraints to add to table columns.
     * @param string $column Name of table field the constraint is being applied to.
     * @param string $constraint Constraint to apply to the table field. Note that
     *        the <b>$column</b> will be appended to the end of the <b>$constraint</b>
     *        unless there is a <i>tilde</i>, <b>~</b>, character in it. If present,
     *        the <b>$column</b> will be added in place of the <i>tilde</i>.
     */
    public function addTableConstraints(string $column, string $constraint): void
    {
        $this->tableConstraints[$column] = $constraint;
    }

    /**
     * Set the <b>WHERE</b> clause object to generate when the next request is performed.
     * @param object $where Either an instance of <i>WhereItem</i> or <i>WhereClause</i>.
     *        Note: If a <i>WhereItem</i> is submitted, it will be added to the <i>WhereClause</i>.
     *        If a <i>WhereClause</i> is submitted, it will be set as the initial value replacing
     *        any previously set values.
     * @throws PdoDbException if an invalid parameter type is submitted.
     */
    public function addToWhere(object $where): void
    {
        if (is_a($where, "Inc\Claz\WhereItem")) {
            $this->whereClause->addItem($where);
        } elseif (is_a($where, "Inc\Claz\WhereClause")) {
            $this->whereClause = $where;
        } else {
            error_log("PdoDb::addToWhere() - where - " . print_r($where, true));
            error_log(print_r(debug_backtrace(), true));
            throw new PdoDbException("PdoDb.php - addToWhere(): Item must be an object of WhereItem or WhereClause");
        }
    }

    /**
     * Specify functions with parameters to list of those to perform
     * @param FunctionStmt|string $function FunctionStmt object or a string with a preformatted
     *        function to include in parameter list. For example, "count(id)".
     * @throws PdoDbException
     */
    public function addToFunctions($function): void
    {
        if (is_string($function) || is_a($function, "Inc\Claz\FunctionStmt")) {
            $this->functions[] = $function;
        } else {
            throw new PdoDbException("PdoDb - addToFunctions(): Parameter number be a string or a FunctionStmt.");
        }
    }

    /**
     * Specify <b>Select</b> object to add to the select list.
     * @param Select $selectStmt Object to include in parameter list.
     */
    public function addToSelectStmts(Select $selectStmt): void
    {
        $this->selectStmts[] = $selectStmt;
    }

    /**
     * Add a <b>Join</b> object to this request.
     * @param Join|array $join Parameter can take several forms:
     *        <ol>
     *          <li><b>Join class object</b></li>
     *          <li><b>Array with 4 values</b>. The values are:
     *            <ol>
     *              <li><b>string</b>: Type of join.</li>
     *              <li><b>string</b>: Table to join.</li>
     *              <li><b>string</b>: Alias for table.</li>
     *              <li><b>OnClause</b>: Object with join information.</li>
     *            </ol>
     *          <li><b>Array with 5 values</b>. The values are:
     *            <ol>
     *              <li><b>string</b>: Type of join.</li>
     *              <li><b>string</b>: Table to join.</li>
     *              <li><b>string</b>: Alias for table.</li>
     *              <li><b>string</b>: Left field to join on.</li>
     *              <li><b>string</b>: Right field value to join on.</li>
     *            </ol>
     *          </li>
     *        </ol>
     * @throws PdoDbException If invalid values are passed.
     */
    public function addToJoins($join): void
    {
        if (is_a($join, "Inc\Claz\Join")) {
            $this->joinStmts[] = $join;
            return;
        }

        if (!is_array($join)) {
            error_log("PdoDb - addToJoins(): parameter join - " . print_r($join, true));
            throw new PdoDbException("PdoDb - addToJoins(): Invalid parameter type specified. See error_log for details.");
        }

        // type, table, alias, OnClause
        // type, table, alias, field, value
        if (count($join) == 4) {
            $type = $join[0];
            $table = $join[1];
            $alias = $join[2];
            $onClause = $join[3];
            if (!is_string($type) || !is_string($table) || !is_string($alias) || !is_a($onClause, "Inc\Claz\OnClause")) {
                if (is_a($onClause, "Inc\Claz\OnClause")) {
                    throw new PdoDbException("PdoDb - addToJoins(): Array submitted. Non-string content where string required.");
                } else {
                    throw new PdoDbException("PdoDb - addToJoins(): Array submitted. Non-class (OnClause) data where class object required.");
                }
            }
            $jn = new Join($type, $table, $alias);
            $jn->setOnClause($onClause);
            $this->joinStmts[] = $jn;
        } elseif (count($join) == 5) {
            $type = $join[0];
            $table = $join[1];
            $alias = $join[2];
            $field = $join[3];
            $value = $join[4];
            if (!is_string($type) || !is_string($table) || !is_string($alias) || !is_string($field) ||
                !is_string($value) && !is_int($value)) {
                throw new PdoDbException("PdoDb - addToJoins(): Array submitted contains non-string fields.");
            }
            $jn = new Join($type, $table, $alias);
            $jn->addSimpleItem($field, $value);
            $this->joinStmts[] = $jn;
        } else {
            throw new PdoDbException("PdoDb - addToJoins(): Array submitted with invalid content.");
        }
    }

    /**
     * Add a <b>CaseStmt</b> object to this request.
     * @param CaseStmt $caseStmt Object to build a <b>CASE</b> statement from.
     */
    public function addToCaseStmts(CaseStmt $caseStmt): void
    {
        if (!isset($this->caseStmts)) {
            $this->caseStmts = [];
        }
        $this->caseStmts[] = $caseStmt;
    }

    /**
     * @param string $statement to perform update on duplicate key.
     */
    public function setOnDuplicateKey(string $statement): void
    {
        $this->onDuplicateKey = "ON DUPLICATE KEY UPDATE " . $statement;
    }

    /**
     * Set the <b>ORDER BY</b> statement object to generate when the next request is performed.
     * Note that this method can be called multiple times to add additional values.
     * @param OrderBy|array|string $orderBy Can take several forms.
     *        1) A string that is the name of the field to order by in ascending order.
     *           Ex: "street_address".
     *        2) An array with two elements. The first is the field name and the second is the
     *           order to sort it by. Ex: array("street_address", "D").
     *        3) An array of arrays where each internal array has two elements as explained in #2 above.
     *        4) An OrderBy object that will replace any previous settings.
     * @throws PdoDbException if an invalid parameter type is found.
     */
    public function setOrderBy($orderBy): void
    {
        if (is_a($orderBy, "Inc\Claz\OrderBy")) {
            $this->orderBy = $orderBy;
        } elseif (is_array($orderBy)) {
            if (is_array($orderBy[0])) {
                foreach ($orderBy as $item) {
                    if (count($item) != 2) {
                        $str = "PdoDb setOrderby - field array is invalid. Must be 'field' and 'order'.";
                        error_log($str);
                        throw new PdoDbException($str);
                    }
                    $this->orderBy->addField($item[0], $item[1]);
                }
            } else {
                if (count($orderBy) != 2) {
                    $str = "PdoDb setOrderby - field array is invalid. Must be 'field' and 'order'.";
                    error_log($str);
                    throw new PdoDbException($str);
                }
                $this->orderBy->addField($orderBy[0], $orderBy[1]);
            }
        } elseif (is_string($orderBy)) {
            $this->orderBy->addField($orderBy);
        } else {
            $str = "PdoDb setOrderBy(): Invalid parameter type. " . print_r($orderBy, true);
            error_log($str);
            throw new PdoDbException($str);
        }
    }

    /**
     * Set the <b>GROUP BY</b> statement object to generate when the next request is performed.
     * Note that this method can be called multiple times to add additional values.
     * @param string|array|DbField $groupBy Can take one of the following forms.
     *        <ol>
     *          <li>A string that is the name of the field to group by. Ex: "street_address".</li>
     *          <li>An ordered array that contains a list of field names to group by. The list is
     *              high to low group by levels.</li>
     *          <li>A <i>DbField</i> object. Ex: new DbField("tax.tax_id"). Needed to properly
     *              encapsulate the field name as `tax`.`tax_id`.
     * @throws PdoDbException if an invalid parameter type is found.
     */
    public function setGroupBy($groupBy): void
    {
        if (is_array($groupBy)) {
            foreach ($groupBy as $item) {
                if (is_a($item, "Inc\Claz\DbField")) {
                    $this->groupBy[] = $item->genParm(true);
                } elseif (is_string($item)) {
                    $this->groupBy[] = $item;
                } else {
                    $str = "PdoDb setGroupBy(): Invalid parameter type. " . print_r($item, true);
                    error_log($str);
                    throw new PdoDbException($str);
                }
            }
        } elseif (is_a($groupBy, "Inc\Claz\DbField")) {
            $this->groupBy[] = $groupBy->genParm(true);
        } elseif (is_string($groupBy)) {
            $this->groupBy[] = $groupBy;
        } else {
            $str = "PdoDb setGroupBy(): Invalid parameter type. " . print_r($groupBy, true);
            error_log($str);
            throw new PdoDbException($str);
        }
    }

    /**
     * Set/add to <b>Havings</b> values using reduced parameter list.
     * @param string $field
     * @param string $operator
     * @param string|DbField $value
     * @param string $connector Optional "AND" or "OR".
     * @throws PdoDbException
     */
    public function setSimpleHavings(string $field, string $operator, $value, string $connector = ""): void
    {
        $having = new Having(false, $field, $operator, $value, false, $connector);
        $this->setHavings($having);
    }

    /**
     * Set the <b>HAVING</b> statement object generate when the next <b>request</b> is performed.
     * Note: This method can be called multiple times to add additional values.
     * @param Having|Havings $havings <b>HAVING</b> or <b>HAVINGS</b> object to add.
     * @throws PdoDbException If parameter is not valid.
     */
    public function setHavings($havings): void
    {
        if (is_a($havings, "Inc\Claz\Having") || is_a($havings, "Inc\Claz\Havings")) {
            $this->havings->addHavings($havings);
        } else {
            error_log(print_r(debug_backtrace(), true));
            $str = "PdoDb setHavings(): Invalid parameter type. " . print_r($havings, true);
            error_log($str);
            throw new PdoDbException($str);
        }
    }

    /**
     * Set a limit on records accessed
     * @param int $limit Value to specify in the <i>LIMIT</i> parameter.
     * @param int $offset (Optional) Number of records to skip before reading the next $limit amount.
     */
    public function setLimit(int $limit, int $offset = 0): void
    {
        $this->limit = ($offset > 0 ? $offset . ", " : "") . $limit;
    }

    /**
     * Updates the list of fields to be excluded from the table fields found in the <i>$_POST</i>
     * or if used, the <i>FAUX POST</i> array. These fields might be present in the <i>WHERE</i>
     * clause but are to be excluded from the <i>INSERT</i> or </i>UPDATE</i> fields. Typically
     * excluded fields are the unique identifier for the record which cannot be updated. However,
     * any field may be specified for exclusion..
     * @param string|array $excludedFields Can be one of the following:
     *        <ol>
     *          <li>A string with <i><u>one</u> field name</i> in it. Ex: <b>"name"</b>.</li>
     *          <li>An ordered array of <i>field names</i>. Ex: <b>array("id", "user_id")</b></li>
     *          <li>An associative array with the <i>key</i> as the <i>field name</i>
     *              and the <i>value</i> as anything (typically 1).<br/>
     *              Ex: array("id" => 1, "name" => 1).</li>`
     *        </ol>
     * @throws PdoDbException if the parameter is not an array.
     */
    public function setExcludedFields($excludedFields): void
    {
        if (is_array($excludedFields)) {
            $idx = 0;
            foreach ($excludedFields as $key => $val) {
                if (is_numeric($key) && intval($key) == $idx) {
                    $this->excludedFields[$val] = 1;
                    $idx++;
                } else {
                    $this->excludedFields[$key] = $val;
                }
            }
        } elseif (is_string($excludedFields)) {
            $this->excludedFields[$excludedFields] = 1;
        } else {
            $this->clearAll();
            $str = "PdoDb - setExcludedFields(): \"\$excludedFields\" parameter is not an array.";
            error_log($str);
            throw new PdoDbException($str);
        }
    }

    /**
     * Prefix value to prepend to the <i>$_POST</i> or <i>FAUX POST</i> field names.
     * @param string $fieldPrefix Contains the prefix characters that are prepended to the
     *        field name from the database for reference on the screen. This allows a screen
     *        that modifies multiple tables to uniquely identify fields of the same name in
     *        each table. For example: The screen contains fields for two tables. Both
     *        table 1 and table 2 contain name and address fields (name, address, city,
     *        state, zip). To allow those fields for table 2 to be identified, a prefix
     *        of "t2" is used for table 2 fields. This means the "name" attribute for
     *        these fields will contain t2_name, t2_address, t2_city, t2_state and t2_zip.
     *        When a <i>PdoDb request</i> is submitted for table 1 fields, no prefix will
     *        be set. Then when the <i>PdoDb request</i> is submitted for table 2, this
     *        field prefix of <b>"t2"</b> will be set.
     */
    public function setFieldPrefix(string $fieldPrefix): void
    {
        $this->fieldPrefix = $fieldPrefix;
    }

    /**
     * Set faux post mode and file.
     * @param array $fauxPost Array to use in place of the <b>$_POST</b> SuperGlobals.
     *        Use the <b>table column name</b> as the index and the value to set the
     *        column to as the value of the array at the column name index.
     *        Ex: $fauxPost['name'] = "New name";
     */
    public function setFauxPost(array $fauxPost): void
    {
        $this->usePost = false;
        $this->fauxPost = $fauxPost;
    }

    /**
     * Specify if all fields are to be selected or not.
     * @param bool $selectAll true to select all field, false if only those select list, function
     *        statements, etc. should be selected. Note that this field does not affect what is
     *        specified to select in the various select list, statement, etc. functions.
     */
    public function setSelectAll(bool $selectAll): void
    {
        $this->selectAll = $selectAll;
    }

    /**
     * Specify the subset of fields that a <i>SELECT</i> is to access.
     * Note that default is to select all fields. This function can be called multiple
     * times to build the list conditionally.
     * @param string|array|DbField $selectList Can take one of two forms.
     *        1) A string with the field name to select from the table.
     *           Ex: "street_address".
     *        2) An array of field names to select from the table.
     *           Ex: array("name", "street_address", "city", "state", "zip").
     *        3) A DbField object. Ex: new DbField("iv.invoice_id", "invoice_id").
     * @throws PdoDbException if an invalid parameter type is found.
     */
    public function setSelectList($selectList): void
    {
        if (is_a($selectList, "Inc\Claz\DbField")) {
            $this->selectList[] = $selectList;
        } elseif (is_array($selectList)) {
            $this->selectList = array_merge($this->selectList, $selectList);
        } elseif (is_string($selectList)) {
            $this->selectList[] = $selectList;
        } else {
            $str = "PdoDb setSelectList(): Invalid parameter type. " . print_r($selectList, true);
            error_log($str);
            throw new PdoDbException($str);
        }
    }

    /**
     * Output SQL string to error log if <b>debug</b> property set.
     * @param string $sql The sql query with parameter placeholders.
     *        Use <i>debugOn()</i> and <i>debugOff()</i> methods to
     *        toggle this option. The <b>$debug</b> parameter in the
     *        constructor  can be used to turn debug on when the object
     *        is instantiated.
     */
    private function debugger(string $sql): void
    {
        if ($this->debug || $this->saveLastCommand) {
            $keys = [];
            $values = $this->keyPairs;
            // build a regular expression for each parameter
            foreach ($this->keyPairs as $key => $value) {
                // Add quotes around the named parameters and ? parameters.
                if (is_string($key)) {
                    $keys[] = '/' . $key . '/';
                } else {
                    $keys[] = '/[?]/';
                }

                // If the value for this is is an array, make it a character separated string.
                if (is_array($value)) {
                    $values[$key] = implode(',', $value);
                }
                // If the value is null, make it a string value of "NULL".
                if (is_null($value)) {
                    $values[$key] = 'null';
                }
            }

            // Walk the array to see if we can add single-quotes to strings
            $count = null;
            array_walk($values,
                function (&$val) {
                    if ($val != "null" && !is_numeric($val) && !is_array($val) && !is_object($val)) {
                        $val = "'" . $val . "'";
                    }
                }
            );
            $sql = preg_replace($keys, $values, $sql, 1, $count);

            // Compact query to be logged
            $sql = preg_replace('/  +/', ' ', str_replace(PHP_EOL, '', $sql));
        }

        if ($this->debug) {
            error_log("PdoDb - debugger($this->debugLabel): $sql");
        }

        if (is_string($sql) && $this->saveLastCommand) {
            $this->lastCommand = $sql;
        }
    }

    /**
     * Retrieves the record ID of the row just inserted.
     * @return int Record ID or 0 in failed attempt to get an id
     * @throws PdoDbException if database error occurs.
     */
    private function lastInsertId(): int
    {
        $sql = 'SELECT last_insert_id()';
        if ($sth = $this->pdoDb->prepare($sql)) {
            if ($sth->execute()) {
                return $sth->fetchColumn();
            }
            return 0;
        } else {
            $this->clearAll();
            error_log("PdoDb - lastInsertId(): Prepare error." . print_r($sth->errorInfo(), true));
            throw new PdoDbException('PdoDb lastInsertId(): Prepare error.');
        }
    }

    /**
     * Check if a specified table exists in the database attached to this object.
     * @param string $table_in to look for. Note that TB_PREFIX will be added if not present.
     * @return bool true if table exists, false if not.
     */
    public function checkTableExists(string $table_in): bool
    {
        try {
            $table = self::addTbPrefix($table_in);
            $sql = "SELECT 1" .
                " FROM `information_schema`.`tables`" .
                " WHERE `table_name` = '$table'" .
                " AND `table_schema` = '{$this->tableSchema}';";
            if ($sth = $this->pdoDb2->prepare($sql)) {
                if ($sth->execute() !== false) {
                    $tmpResult = $sth->fetchAll(PDO::FETCH_ASSOC);
                    $result = [];
                    foreach ($tmpResult as $key => $value) {
                        $result[strtolower($key)] = $value;
                    }
                    return !empty($result);
                }
            }
        } catch (Exception $exp) {
            error_log("PdoDb checkTableExists(): Error: " . $exp->getMessage());
        }
        return false;
    }

    /**
     * Check to see if a column (aka field) exists in a table of the database attached to this object.
     * @param string $table_in name of the table to get fields for. Note that TB_PREFIX will be added if not present.
     * @param string $column to search for.
     * @return bool true if column exists in the table, false if not.
     */
    public function checkFieldExists(string $table_in, string $column): bool
    {
        try {
            $table = self::addTbPrefix($table_in);
            $sql = "SELECT 1" .
                " FROM `information_schema`.`columns`" .
                " WHERE `column_name`= '$column'" .
                " AND `table_name` = '$table'" .
                " AND `table_schema` = '{$this->tableSchema}';";
            if (($sth = $this->pdoDb2->prepare($sql)) !== false) {
                if ($sth->execute() !== false) {
                    $tmpResult = $sth->fetchAll(PDO::FETCH_ASSOC);
                    $result = [];
                    foreach ($tmpResult as $key => $value) {
                        $result[strtolower($key)] = $value;
                    }
                    return !empty($result);
                }
            }
        } catch (Exception $exp) {
            error_log("PdoDb checkFieldExists(): Error: " . $exp->getMessage());
        }
        return false;
    }

    /**
     * Get a list of fields (aka columns) in a specified table.
     * @param string $table_in Name of the table to get fields for.
     * @return array Column names from the table. An empty array is
     *         returned if no columns found.
     */
    private function getTableFields(string $table_in): array
    {
        try {
            $table = $table_in;
            $columns = [];

            // @formatter:off
            $sql = "SELECT `column_name`" .
                   " FROM `information_schema`.`columns`" .
                   " WHERE `table_schema`=:table_schema" .
                   " AND `table_name`  =:table;";
            $tokenPairs = [
                ':table_schema'=> $this->tableSchema,
                ':table'       => $table
            ];
            if ($sth = $this->pdoDb2->prepare($sql)) {
                if ($sth->execute($tokenPairs)) {
                    while ($row = $sth->fetch(PDO::FETCH_ASSOC)) {
                        $nam = isset($row['column_name']) ? $row['column_name'] : $row['COLUMN_NAME'];
                        $columns[$nam] = "";
                        $sql = "SELECT `constraint_name`" .
                               " FROM `information_schema`.`key_column_usage`" .
                               " WHERE `column_name` =:column_name" .
                               " AND `table_schema`=:table_schema" .
                               " AND `table_name`  =:table;";
                        $tokenPairs = [
                            ':column_name' => $nam,
                            ':table_schema'=> $this->tableSchema,
                            ':table'       => $table
                        ];
                        if ($tth = $this->pdoDb2->prepare($sql)) {
                            if ($tth->execute($tokenPairs)) {
                                while ($row2 = $tth->fetch(PDO::FETCH_ASSOC)) {
                                    if (!empty($columns[$nam])) {
                                        $columns[$nam] .= ":";
                                    }
                                    $columns[$nam] .= strtoupper($row2['constraint_name']);
                                }
                            }
                        }
                    } // end while
                }
            }
        } catch (Exception $exp) {
            error_log("PdoDb getTableFields(): Error: " . $exp->getMessage());
        }
        return $columns;
    }

    /**
     * Construct column, value and PDO parameter list for the specified request.
     * @param string $request "INSERT" or "UPDATE".
     * @param array $valuePairs Array of values keyed by the associated column.
     * @param int $tokenCnt Value used to make unique token names.
     * @return string columns and associated values formatted to append to the sql statement
     *         for the specified <b>$request</b>.
     */
    private function makeValueList(string $request, array $valuePairs, int &$tokenCnt): string {
        $sep = "";
        $colList = "";
        $valList = "";
        $colTokenList = "";
        $insert = $request == "INSERT";
        $update = $request == "UPDATE";
        $idx = 0;
        foreach ($valuePairs as $column => $value) {
            $token = ":" . self::makeToken($column, $tokenCnt);
            $this->keyPairs[$token] = $value;
            // Add value setting if token is not excluded from the list of values being inserted or updated,
            if (empty($this->excludedFields) || !array_key_exists($column, $this->excludedFields)) {
                if ($insert) {
                    $colList .= $sep . "`" . $column . "`";
                    $valList .= $sep . $token;
                } elseif ($update) {
                    $colTokenList .= $sep . "`" . $column . "` = " . $token;
                }
                $sep = "," . (++$idx % 5 == 0 ? "\n " : " ");
            }
        }

        $sql = "";
        if ($insert) {
            $sql = "($colList)\n VALUES ($valList)";
        } elseif ($update) {
            $sql = $colTokenList;
        }
        return $sql;
    }

    /**
     * Begin a transaction.
     * @throws PdoDbException if a transaction is already in process.
     */
    public function begin(): void {
        if ($this->debug) {
            error_log("begin()");
        }
        if ($this->transaction) {
            $this->rollback();
            throw new PdoDbException("PdoDb begin(): Called when transaction already in process.");
        }
        $this->clearAll();
        $this->transaction = true;
        $this->pdoDb->beginTransaction();
    }

    /**
     * Rollback actions performed as part of the current transaction.
     * @throws PdoDbException
     */
    public function rollback(): void {
        if ($this->transaction) {
            if ($this->debug) {
                error_log("rollback()");
            }
            $this->pdoDb->rollback();
            $this->transaction = false;
            $this->clearAll();
        } else {
            $this->clearAll();
            throw new PdoDbException("PdoDb rollback(): Called when no transaction is in process.");
        }
    }

    /**
     * Commit actions performed in this transaction.
     * @throws PdoDbException if called when no transaction is in process.
     */
    public function commit(): void {
        if ($this->transaction) {
            if ($this->debug) {
                error_log("commit()");
            }
            $this->pdoDb->commit();
            $this->transaction = false;
            $this->clearAll();
        } else {
            $this->clearAll();
            throw new PdoDbException("PdoDb commit(): Called when no transaction is in process.");
        }
    }

    /**
     * Enclose field in <b>back-ticks</b> and add table alias if
     * specified and not one already present.
     * @param string $field Field to modify.
     * @param string $alias Table alias or empty if none.
     * @return string Updated field.
     */
    public static function formatField(string $field, string $alias = ""): string {
        if (preg_match('/^["\']/', $field)) {
            return $field;
        }
        $matches = [];
        if (preg_match('/^([a-z_]+)\.(.*)$/', $field, $matches)) {
            // Already an alias present
            $parts = [];
            if (preg_match('/(.*) +([aA][sS]) +(.*)/', $matches[2], $parts)) {
                // x.y AS z
                $field = '`' . $matches[1] . '`.' . ($parts[1] == '*' ? $parts[1] : '`' . $parts[1] . '`') .
                         ' AS ' . $parts[3];
            } else {
                // x.y
                $field = '`' . $matches[1] . '`.' . ($matches[2] == '*' ? $matches[2] : '`' . $matches[2] . '`');
            }
        } elseif (!empty($alias)) {
            // Needs to have alias added.
            $field = '`' . $alias . '`.`' . $field . '`';
        } else {
            $field = '`' . $field . '`';
        }
        return $field;
    }

    /**
     * Add the <b>SimpleInvoices</b> database table prefix, <b>si_</b>, if not already present.
     * @param string $table Table name that a prefix will be prepended to.
     * @return string Updated table name.
     */
    public static function addTbPrefix(string $table): string {
        $pattern = "/^" . TB_PREFIX . ".*$/";
        if (preg_match($pattern, $table) != 1) {
            return TB_PREFIX . $table;
        }
        return $table;
    }

    /**
     * Set no output to error_log condition for errors that are thrown.
     * Set only for the current request. If debug option is set, the error
     * will be reported in the error_log.
     */
    public function setNoErrorLog(): void {
        $this->noErrorLog = true;
    }

    /**
     * Dynamically builds and executes a PDO request for a specified table.
     * @param string $request Type of request. Valid settings are: <b>SELECT</b>,
     *        <b>INSERT</b>, <b>UPDATE</b> and <b>DELETE</b>. Note that letter
     *        case of this parameter does not matter.
     * @param string $table Database table name.
     * @param string $alias (Optional) Alias for table name. Note that the alias need
     *         be in the select list. If not present, it will be added to selected fields.
     * @return mixed Result varies with the request type.
     *         <b>INSERT</b> returns the auto increment unique ID (or 0 if no such field),
     *         <b>SELECT</b> returns the associative array for selected rows or an empty array if no rows are found,
     *         <b>SHOW TABLES</b> returns the associative array for tables <i>LIKE</i> the specified table or empty array,
     *         <b>SHOW</b> returns the numeric array of specified <b>SHOW</b> request,
     *         otherwise <b>true</b> on success or <b>false</b> on failure.
     * @throws PdoDbException if any unexpected setting or missing information is encountered.
     */
    public function request(string $request, string $table, string $alias = "") {

        if ($this->debug) {
            $this->debugMicroTime = microtime(true);
        }
        $request = strtoupper($request);
        $table = self::addTbPrefix($table);
        $sql = "";
        $valuePairs = [];
        $this->keyPairs = [];
        $tokenCnt = 0;

        if ($request != "DROP") {
            if ($request == "ALTER TABLE") {
                if (empty($this->tableConstraints)) {
                    throw new PdoDbException("PdoDb - request():");
                }

                foreach ($this->tableConstraints as $column => $constraint) {
                    if (preg_match('/compound/', $column)) {
                        $column = preg_replace('/compound *(\(.*\)).*$/', '\1', $column);
                    }

                    if (strstr($constraint, '~') === false) {
                        $constraint .= " " . $column;
                    } else {
                        $constraint = preg_replace('/~/', $column, $constraint);
                    }

                    $sql .= "ALTER TABLE `$table` {$constraint};";
                }
            } elseif ($request == "CREATE TABLE") {
                foreach ($this->tableColumns as $column => $structure) {
                    if (empty($sql)) {
                        $sql = "(";
                    } else {
                        $sql .= ", ";
                    }
                    $sql .= $column . " " . $structure;
                }

                if (empty($this->tableEngine)) {
                    $this->clearAll();
                    throw new PdoDbException("PdoDb - request(): No engine specified for CREATE TABLE ($table).");
                }

                if (empty($sql)) {
                    throw new PdoDbException("PdoDb - request(): No columns specified to CREATE TABLE ($table).");
                }

                $sql .= ") ENGINE = " . $this->tableEngine . ";";
            } elseif ($request == "SHOW TABLES") {
                $sql = $request . " LIKE '{$table}'";
            } else {
                if (!($columns = $this->getTableFields($table))) {
                    $this->clearAll();
                    throw new PdoDbException("PdoDb - request(): Invalid table, $table, specified.");
                }

                $onDuplicateKeyUpdate = empty($this->onDuplicateKey) ? '' : $this->onDuplicateKey;

                // Build WHERE clause and get value pair list for tokens in the clause.
                $where = "";
                if (!$this->whereClause->isEmpty()) {
                    $where = $this->whereClause->build($this->keyPairs);
                    $tokenCnt += $this->whereClause->getTokenCnt();
                }

                // Build ORDER BY statement
                $order = $this->orderBy->build();

                // Build GROUP BY
                $group = empty($this->groupBy) ? "" : "GROUP BY " . implode(',', $this->groupBy);

                // Build HAVING statement
                $havings = $this->havings->build();

                // Build LIMIT
                $limit = $this->limit == 0 ? '' : " LIMIT {$this->limit}";
                // Make an array of paired column name and values. The column name is the
                // index and the value is the content at that column.
                foreach ($columns as $column => $values) {
                    // Check to see if a field prefix was specified and that there is no
                    // table alias present. If so, prepend the prefix followed by an underscore.
                    if ($this->usePost && isset($_POST[$column]) ||
                        !$this->usePost && isset($this->fauxPost[$column])) {
                        $valuePairs[$column] = $this->usePost ? $_POST[$column] : $this->fauxPost[$column];
                    }
                }
            }
        }

        // @formatter:off
        $useValueList = $request != "ALTER TABLE"  &&
                        $request != "CREATE TABLE" &&
                        $request != "SHOW TABLES"  &&
                        $request != "DROP";
        switch ($request) {
            case "ALTER TABLE":
            case "SHOW TABLES":
                // $sql contains the complete command
                break;

            case "CREATE TABLE":
                $sql = "CREATE TABLE `{$table}` $sql";
                break;

            case "DROP":
                $sql = "DROP TABLE IF EXISTS `{$table}` $sql";
                break;

            case "SELECT":
                $useValueList = false;
                if ($this->selectAll) {
                    $list = "*";
                } elseif (empty($this->selectList) && empty($this->functions) && empty($this->caseStmts)) {
                    $list = "*"; // set if nothing specified, select all.
                } else {
                    $list = "";
                }

                if (!empty($this->selectList)) {
                    foreach($this->selectList as $column) {
                        $isADbf = is_a($column, "Inc\Claz\DbField") ? true : false;
                        if (!empty($list)) {
                            $list .= ', ';
                        }

                        if ($isADbf) {
                            $list .= $column->genParm();
                        } else {
                            $list .= self::formatField($column, $alias);
                        }
                    }
                }

                if (!empty($this->functions)) {
                    foreach($this->functions as $function) {
                        if (is_a($function, "Inc\Claz\FunctionStmt")) {
                            $function = $function->build();
                        }
                        if (!empty($list)) {
                            $list .= ", ";
                        }
                        $list .= $function;
                    }
                }

                if (!empty($this->selectStmts)) {
                    foreach($this->selectStmts as $selectStmt) {
                        if (!empty($list)) {
                            $list .= ", ";
                        }
                        $list .= $selectStmt->build($this->keyPairs);
                        $tokenCnt += $selectStmt->getTokenCnt();
                    }
                }

                if (!empty($this->caseStmts)) {
                    foreach($this->caseStmts as $caseStmt) {
                        if (!empty($list)) {
                            $list .= ", ";
                        }
                        $list .= $caseStmt->build($this->keyPairs);
                    }
                }

                $sql  = "SELECT " . ($this->distinct ? "DISTINCT " : "") . "$list FROM `$table` " .
                        (!empty($alias) ? "`{$alias}` " : "") . "\n";

                if (!empty($this->joinStmts)) {
                    foreach($this->joinStmts as $join) {
                        $sql .= $join->build($this->keyPairs) . "\n";
                    }
                }

                break;

            case "INSERT":
                $sql /** @lang TEXT */ = "INSERT INTO `{$table}` \n";
                break;

            case "UPDATE":
                $sql /** @lang TEXT */ = "UPDATE `{$table}` SET \n";
                break;

            case "DELETE":
                $sql /** @lang TEXT */  = "DELETE FROM `{$table}` \n";
                break;

            default:
                error_log("PdoDb - request(): Request, $request, not implemented.");
                $this->clearAll();
                throw new PdoDbException("PdoDb - request():  Request, $request, not implemented.");
        }
        if ($useValueList) {
            $lclValueList = $this->makeValueList($request, $valuePairs, $tokenCnt) . "\n";
            $sql .= $lclValueList;
        }

        $sql .= (empty($onDuplicateKeyUpdate) ? "" : " " . $onDuplicateKeyUpdate) .
                (empty($where  ) ? "" : " " . $where   . "\n") .
                (empty($group  ) ? "" : " " . $group   . "\n") .
                (empty($havings) ? "" : " " . $havings . "\n") .
                (empty($order  ) ? "" : " " . $order   . "\n") .
                (empty($limit  ) ? "" : " " . $limit);
        // @formatter:on

        return $this->query($sql, $this->keyPairs);
    }

    /**
     * Perform query for specified SQL statement and needed value pairs.
     * Note: This method provides the ability to perform an externally formatted
     * SQL statement with or without value pairs.
     * @param string $sql SQL statement to be performed.
     * @param array $valuePairs Array of value pairs. This parameter is optional
     *        and only needs to be specified if bind values are needed.
     *        Example: array(':id' => '7', ':domain_id' => '1');
     * @return int|array|bool Result varies with the request type.
     *         <b>INSERT</b> returns the auto increment unique ID (or 0 if table contains not "id" field),
     *         <b>SELECT</b> returns the associative array for selected rows or an empty array if no rows are found,
     *         <b>SHOW TABLES</b> returns the associative array for tables <i>LIKE</i> the specified table or empty array,
     *         <b>SHOW</b> returns the numeric array of specified <b>SHOW</b> request,
     *         otherwise <b>true</b> on success or <b>false</b> on failure.
     * @throws PdoDbException If unable to bind values or execute request.
     */
    public function query(string $sql, array $valuePairs = [])
    {
        $this->lastCommand = ""; // Clear the previous last command.
        $this->debugger($sql);
        $this->saveLastCommand = false; // reset flag now that it has been saved.

        if (!($sth = $this->pdoDb->prepare($sql))) {
            if ($this->debug || !$this->noErrorLog) {
                error_log("PdoDb - query(): Prepare error." . print_r($sth->errorInfo(), true));
            }
            $this->clearAll();
            throw new PdoDbException('PdoDb query(): Prepare error.');
        }

        if (!empty($valuePairs) && is_array($valuePairs)) {
            foreach ($valuePairs as $key => $val) {
                $pattern = "/[^a-zA-Z0-9_\-]" . $key . "[^a-zA-Z0-9_\-]|[^a-zA-Z0-9_\-]" . $key . "$/";
                if (preg_match($pattern, $sql) == 1) {
                    if (!$sth->bindValue($key, $val)) {
                        $this->clearAll();
                        throw new PdoDbException('PdoDb - query(): Unable to bind values.');
                    }
                }
            }
        }

        if (!$sth->execute()) {
            $tmp = $this->debug;
            $this->debug = true; // force debugger on
            $this->debugger($sql);
            $this->debug = $tmp;
            if ($this->debug || !$this->noErrorLog) {
                error_log("PdoDb - query(): Execute error." . print_r($sth->errorInfo(), true));
                error_log("PdoDb - backtrace: " . print_r(debug_backtrace(), true));
            }
            $this->clearAll();
            throw new PdoDbException('PdoDb - query(): Execute error. See error_log.');
        }

        $parts = explode(' ', $sql);
        $request = strtoupper($parts[0]);
        if ($request == "INSERT") {
            $result = $this->lastInsertId();
        } elseif ($request == "SELECT" ||
            $request == "SHOW TABLES") {
            $result = $sth->fetchAll(PDO::FETCH_ASSOC);
        } elseif ($request == "SHOW") {
            $result = $sth->fetchAll(PDO::FETCH_NUM);
        } else {
            $result = true;
        }

        if ($this->debug) {
            $msc = microtime(true) - $this->debugMicroTime;
            error_log("Processing time: " . $msc * 1000 . "ms"); // in milliseconds
        }

        // Don't clear the transaction setting.
        $this->clearAll(false);
        return $result;
    }
}
