<?php

namespace Inc\Claz;

/**
 * Class Join
 * @package Inc\Claz
 */
class Join
{
    private const PREFIX = TB_PREFIX;
    private const JOIN_TYPE = '/^(INNER|LEFT|RIGHT|FULL)$/';

    private GroupBy $groupBy;
    private OnClause $onClause;
    /**
     * @var SELECT|string
     */
    private $table;
    private string $tableAlias;
    private string $type;

    /**
     * Make a JOIN statement
     * @param string $type Join type, <b>INNER</b>, <b>LEFT</b>, <b>RIGHT</b> or <b>FULL</b>.
     * @param string|SELECT $table Database table to join. If not present, the database prefix will be added.
     * @param string $tableAlias Alias for table to use for column name references.
     */
    public function __construct(string $type, $table, string $tableAlias = "")
    {
        $this->type = strtoupper($type);
        if (preg_match(self::JOIN_TYPE, $this->type) != 1) {
            error_log("Join::__construct() - Invalid type, $type, specified.");
        } else {
            if (is_a($table, "Inc\Claz\Select")) {
                $this->table = $table;
            } else {
                $this->table = Join::addPrefix($table);
            }
            $this->tableAlias = $tableAlias;
            $this->groupBy = new GroupBy();
            $this->onClause = new OnClause();
        }
    }

    private static function addPrefix(string $table): string
    {
        $pattern = '/^' . self::PREFIX . '.*$/';
        if (preg_match($pattern, $table) != 1) {
            return TB_PREFIX . $table;
        }
        return $table;
    }

    /**
     * Add a simple item to the <b>OnClause</b>.
     * @param string $field Field (aka column) of table to be joined or available in the scope
     *        of fields from tables in the join statement.
     * @param DbField|string $value Value to use in the test. This can be a constant or a field in
     *        the table being joined to. Note that if this is a table field, the <i>DbField</i>
     *        class should be used to render it. Ex: obj->addSimpleItem(iv.id, new DbField(ii.id)).
     * @param string $connector Connector to the next item, <b>AND</b> or <b>OR</b>. If not
     *        specified, this is the last item in the <b>OnClause</b>.
     * @throws PdoDbException
     */
    public function addSimpleItem(string $field, $value, string $connector="")
    {
        $this->onClause->addSimpleItem($field, $value, $connector);
    }

    /**
     * Specify the <b>ON</b> clause to qualify join this table to the selection.
     * @param OnClause $onClause Object of class type <b>OnClause</b>.
     * @throws PdoDbException
     */
    public function setOnClause(OnClause $onClause)
    {
        if (!$this->onClause->isEmpty()) {
            throw new PdoDbException("Join setOnClause(): Attempt to set multiple \"OnClause\" statements.");
        }
        $this->onClause = $onClause;
    }

    /**
     * Add a <b>GROUP BY</b> object for this join.
     * @param GroupBy $groupBy
     */
    public function addGroupBy(GroupBy $groupBy)
    {
        $this->groupBy = $groupBy;
    }

    /**
     * Build the <b>JOIN<\b> statement from the specified components.
     * @param &array $keyPairs Array of PDO token and value pairs to bind to the PDO statement.
     *              Note that this array is initialized to empty by this method.
     * @return string <b>JOIN</b> statement.
     * @throws PdoDbException
     */
    public function build(&$keypairs)
    {
        $join = $this->type . " JOIN ";
        if (is_a($this->table, "Inc\Claz\Select")) {
            $join .= "(" . $this->table->build($keypairs) . ") ";
        } else {
            $join .= "`" . $this->table . "` ";
        }
        if (!empty($this->tableAlias)) {
            $join .= "AS " . $this->tableAlias . " ";
        }
        if (!empty($this->onClause)) {
            $join .= $this->onClause->build($keypairs);
        }
        if (!$this->groupBy->isEmpty()) {
            $join .= " " . $this->groupBy->build();
        }
        return $join;
    }
}