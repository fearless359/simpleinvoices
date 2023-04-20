<?php

namespace Inc\Claz;

/**
 * Request class.
 * When multiple database requests need to be processed with a single
 * <i>transaction</i>, <b>Request</b> objects can be collected in an
 * array for processing.
 * @author Rich
 * Apr 16, 2016
 */
class Request
{
    private array $excludedFields;
    private array $fauxPostList;
    private int $limit;
    private OrderBy $orderBy;
    private string $request;
    private array $selectList;
    private string $table;
    private WhereClause $whereClause;

    /**
     * Class constructor
     * @param string $request Valid values are "SELECT", "INSERT", "UPDATE", "DELETE".
     * @param string $table Name of database table to perform <b>$request</b> on.
     */
    public function __construct(string $request, string $table)
    {
        // @formatter:off
        $this->request        = $request;
        $this->table          = $table;
        $this->limit          = 0;
        $this->excludedFields = [];
        $this->orderBy        = new OrderBy();
        $this->selectList     = [];
        $this->fauxPostList   = [];
        $this->whereClause    = new WhereClause();
        // @formatter:on
    }

    /**
     * Add a simple <b>WhereItem</b> that test for equality.
     * @param string $field The actual name of the field (column) in the table. This is
     *        a required parameter and <b>MUST</b> exist in the table.
     * @param array|string $value Value to use in the test. Note for <b>BETWEEN</b> this will be:
     *        <b>array(beginVal,endVal)</b>.
     * @param string $connector The "AND" or "OR" connector if additional terms will be
     *        clause. Optional parameter.
     * @throws PdoDbException
     */
    public function addSimpleWhere(string $field, array|string $value, string $connector = ""): void
    {
        $this->addWhereItem(false, $field, "=", $value, false, $connector);
    }

    /**
     * addWhereItem
     * @param bool $open_paren Set to <b>true</b> if an opening parenthesis should be
     *        inserted before this term; otherwise set to <b>false</b>.
     * @param string $field The actual name of the field (column) in the table. This is
     *        a required parameter and <b>MUST</b> exist in the table.
     * @param string $operator Valid SQL comparison operator to the <b>$field</b> record
     *        content test against the <b>$value</b> parameter. Currently only the relational
     *        operator are allowed: <b>=</b>, <b><></b>, <b><</b>, <b>></b>, <b><=</b> and <b>>=</b>.
     * @param array|int|string|DbField $value Value to use in the test. Note for <b>BETWEEN</b> this will be: <b>array(beginVal,endVal)</b>.
     * @param bool $close_paren Set to <b>true</b> if a closing parenthesis should be
     *        inserted after this term; otherwise set to <b>false</b>.
     * @param string $connector The "AND" or "OR" connector if additional terms will be clause. Optional parameter.
     * @throws PdoDbException
     */
    public function addWhereItem(bool   $open_paren, string $field, string $operator, DbField|array|int|string $value, bool $close_paren,
                                 string $connector = ""): void
    {
        $whereItem = new WhereItem($open_paren, $field, $operator, $value, $close_paren, $connector);
        $this->whereClause->addItem($whereItem);
    }

    /**
     * Add a field to order by and its sort attribute.
     * @param array|string $field The following forms are valid:
     *          <i>string</i> - A <i>field name</i> to be added to the collection
     *                          of ordered items with the specified <b>$order</b>.
     *          <i>array</i>  - An array of <i>field names</i> or of <i>arrays</i>.<br/>
     *                          If an <i>array of field names</i>, each <i>field name</i> is added
     *                          to the list of ordered items with default order of <b>ASC</b>.<br/>.
     *                          If an <i>array of arrays</i>, each element array can have <i>one</i>
     *                          or <i>two</i> elements. Element arrays of <i>two</dimensions contains
     *                          a <i>field name</i> for the first index and a sort order value in the
     *                          second element. Valid sort order values are: <b>A</b>, <b>ASC</b>, <b>D</b>
     *                          or <b>DESC</b>. Element arrays of <i>one</i> dimension contains a
     *                          <i>field name</i> and will use the value specified in the <b>$order</b>
     *                          parameter field for sorting.
     * @param string $order Order <b>A</b> ascending, <b>D</b> descending. Defaults to <b>A</b>.
     * @throws PdoDbException if either parameter does not contain the form and values specified for them.
     * @noinspection PhpUnused
     */
    public function addOrderBy(array|string $field, string $order = "A"): void
    {
        $this->orderBy->addField($field, $order);
    }

    /**
     * Specify the subset of fields that a <i>SELECT</i> is to access.
     * Note that default is to select all fields.
     * @param array|string $selectList Can take one of two forms.
     *        1) A string with the field name to select from the table.
     *           Ex: "street_address".
     *        2) An array of field names to select from the table.
     *           Ex: array("name", "street_address", "city", "state", "zip").
     * @noinspection PhpUnused
     */
    public function addSelectList(array|string $selectList): void
    {
        if (is_array($selectList)) {
            foreach ($selectList as $field) {
                if (!in_array($field, $this->selectList)) {
                    $this->selectList[] = $field;
                }
            }
        } elseif (!in_array($selectList, $this->selectList)) {
            $this->selectList[] = $selectList;
        }
    }

    /**
     * Adds the list of item and values that will be processed by the request.
     * @param array $fauxPostList An <i>associative array</i> with the <i>field name</i> as the
     *              index and the <b>field value</b> as the content. Note that if the <b>request</b>
     *              is an <i>INSERT</i>, the <b>field value</b> is not used.
     */
    public function addFauxPostList(array $fauxPostList): void
    {
        foreach ($fauxPostList as $field => $value) {
            $this->fauxPostList[$field] = $value;
        }
    }

    /**
     * Set faux post mode and file.
     * @param array $fauxPost Array to use in place of the <b>$_POST</b> Superglobal.
     *        Use the <b>table column name</b> as the index and the value to set the
     *        column to as the value of the array at the column name index.
     *        Ex: $fauxPost['name'] = "New name";
     */
    public function setFauxPost(array $fauxPost): void
    {
        if (empty($this->fauxPostList)) {
            $this->fauxPostList = $fauxPost;
        } else {
            $this->addFauxPostList($fauxPost);
        }
    }

    /**
     * Updates the list of fields to be excluded from the table fields found in the <i>$_POST</i>
     * or if used, the <i>FAUX POST</i> array. These fields might be present in the <i>WHERE</i>
     * clause but are to be excluded from the <i>INSERT</i> or </i>UPDATE</i> fields. Typically
     * excluded fields are the unique identifier for the record which cannot be updated. However,
     * any field may be specified for exclusion..
     * @param array|string $excludedFields Can be one of the following:
     *        <ol>
     *          <li>A string with <i><u>one</u> field name</i> in it. Ex: <b>"name"</b>.</li>
     *          <li>An ordered array of <i>field names</i>. Ex: <b>array("id", "user_id")</b></li>
     *          <li>An associative array with the <i>key</i> as the <i>field name</i>
     *              and the <i>value</i> as anything (typically 1).<br/>
     *              Ex: array("id" => 1, "name" => 1).</li>`
     *        </ol>
     * @throws PdoDbException if the parameter is not an array.
     */
    public function setExcludedFields(array|string $excludedFields): void
    {
        if (is_array($excludedFields)) {
            $idx = 0;
            foreach ($excludedFields as $key => $val) {
                if (is_numeric($key) && intval($key) == $idx) {
                    $this->excludedFields[$val] = 1;
                } else {
                    $this->excludedFields[$key] = $val;
                }
            }
        } elseif (is_string($excludedFields)) {
            $this->excludedFields[$excludedFields] = 1;
        } else {
            $str = "PdoDb - setExcludedFields(): \"\$excludedFields\" parameter is not an array.";
            error_log($str);
            throw new PdoDbException($str);
        }
    }

    /**
     * Set a limit on records accessed
     * @param int $limit Value to specify in the <i>LIMIT</i> parameter.
     * @param int $offset Number of records to skip before reading the next $limit amount.
     * @noinspection PhpUnused
     */
    public function setLimit(int $limit, int $offset = 0): void
    {
        $this->limit = ($offset > 0 ? $offset . ", " : "") . $limit;
    }

    /**
     * Test to see if this is an add request.
     * @return bool $request
     * @noinspection PhpUnused
     */
    public function isAdd(): bool
    {
        return $this->request == "INSERT";
    }

    /**
     * getter for class property
     * @return string $table Table processed by this request.
     * @noinspection PhpUnused
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Perform this request.
     * @param PdoDb $pdoDb
     * @return int|array|bool Result of the request.
     * @throws PdoDbException if an error is thrown when the <b>request</b> is performed.
     * @noinspection PhpUnused
     */
    public function performRequest(PdoDb $pdoDb): array|bool|int
    {
        try {
            // @formatter:off
            if (!empty($this->fauxPostList)  ) {
                $pdoDb->setFauxPost($this->fauxPostList);
            }
            if ($this->limit > 0) {
                $pdoDb->setLimit($this->limit);
            }
            if (!empty($this->orderBy)) {
                $pdoDb->setOrderBy($this->orderBy);
            }
            if (!empty($this->selectList)) {
                $pdoDb->setSelectList($this->selectList);
            }
            if (!empty($this->whereClause)) {
                $pdoDb->addToWhere($this->whereClause);
            }
            if (!empty($this->excludedFields)) {
                $pdoDb->setExcludedFields($this->excludedFields);
            }
            // @formatter:on

            return $pdoDb->request($this->request, $this->table);
        } catch (PdoDbException $pde) {
            throw new PdoDbException("Request::performRequest() - Error: " . $pde->getMessage());
        }
    }

    /**
     * Describe what the table the request is for.
     * @return string Description of the request
     * @noinspection PhpUnused
     */
    public function describe(): string
    {
        return "$this->request for $this->table";
    }
}
