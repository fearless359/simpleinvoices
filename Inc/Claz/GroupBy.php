<?php

namespace Inc\Claz;

/**
 * Class GroupBy
 * @package Inc\Claz
 */
class GroupBy
{
    private array $fields;

    /**
     * GroupBy class constructor
     * @param string|DbField|array $field Highest priority fields to <b>GROUP BY</b>.
     */
    public function __construct($field = [])
    {
        if (is_array($field)) {
            $this->fields = $field;
        } else {
            $this->fields = [$field];
        }
    }

    /**
     * Test to see if this is an empty object.
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->fields);
    }

    /**
     * Build the <b>GROUP BY</b> statement.
     * @return string <b>GROUP BY</b> statement built from specified fields.
     */
    public function build(): string
    {
        if ($this->isEmpty()) {
            return '';
        }

        $stmt = "GROUP BY ";
        $first = true;
        foreach ($this->fields as $field) {
            if ($first) {
                $first = false;
            } else {
                $stmt .= ", ";
            }
            $stmt .= PdoDb::formatField($field);
        }
        return $stmt;
    }
}
