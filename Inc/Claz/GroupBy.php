<?php
namespace Inc\Claz;

/**
 * Class GroupBy
 * @package Inc\Claz
 */
class GroupBy {
    private $fields;

    /**
     * GroupBy class constructor
     * @param DbField $field Highest priority fields to <b>GROUP BY</b>.
     */
    public function __construct($field) {
        if (is_array($field)) $this->fields = $field;
        else $this->fields = array($field);
    }

    /**
     * Build the <b>GROUP BY</b> statement.
     * @param array $keypairs (Optional) Parameter exists for function call compatibility
     *        with other <i>PdoDb</i> class SQL build objects. 
     * @return string <b>GROUP BY</b> statement built from specified fields.
     */
    public function build($keypairs = null) {
        // Eliminates unused warning
        if (!isset($keypairs)) {
            $keypairs = null;
        }
        $stmt = "GROUP BY ";
        $first = true;
        foreach ($this->fields as $field) {
            if ($first) $first = false;
            else $stmt .= ", ";
            $stmt .= PdoDb::formatField($field);
        }
        return $stmt;
    }
}