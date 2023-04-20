<?php

namespace Inc\Claz;

/**
 * DbField class
 * @author Rich
 * Jul 21, 2016
 */
class DbField
{
    private string $alias;

    private int|string $field;

    /**
     * Class constructor
     * @param string|int $field Field name or integer constant.
     * @param string $alias (Optional) Alias for this field. Specify only if needed.
     */
    public function __construct(string|int $field, string $alias = "")
    {
        $this->field = $field;
        $this->alias = $alias;
    }

    /**
     * Generate the parameter for this field to use in SQL statements.
     * @param bool $aliasOnly (Optional) <b>true</b> if specified, only the alias
     *        is returned. <b>false</b> (default) the full phrase is returned.
     *        The <b>true</b> setting is for generating the <i>GROUP BY</i> statement.
     * @return string Field name encapsulated in back-tic for use in SQL statement.
     */
    public function genParm(bool $aliasOnly = false): string
    {
        if (is_int($this->field)) {
            $result = $this->field;
        } else {
            $result = PdoDb::formatField($this->field);
        }
        if (!empty($this->alias)) {
            if ($aliasOnly) {
                $result = $this->alias;
            } else {
                $result .= " AS " . $this->alias;
            }
        }
        return $result;
    }
}
