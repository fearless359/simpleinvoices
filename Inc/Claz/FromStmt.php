<?php

namespace Inc\Claz;

/**
 * Class FromStmt
 * @package Inc\Claz
 */
class FromStmt
{
    private array $table;

    /**
     * Class constructor
     * @param string $table Table name.
     * @param string $alias Alias to be assigned to this table.
     */
    public function __construct(string $table = "", string $alias = "")
    {
        $this->table = [];
        if (!empty($table)) {
            $this->addTable($table, $alias);
        }
    }

    /**
     * Check to see if the object is logically empty.
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->table);
    }

    /**
     * Add a table to the <b>FROM</b> list.
     * @param string $table
     * @param string $alias
     */
    public function addTable(string $table, string $alias = ""): void
    {
        $this->table[] = [$table, $alias];
    }

    /**
     * Build <b>FROM</b> statement.
     * @return string
     */
    public function build(): string
    {
        if ($this->isEmpty()) {
            return "";
        }
        $stmt = "FROM ";
        $first = true;
        foreach ($this->table as $table) {
            if ($first) {
                $first = false;
            } else {
                $stmt .= ", ";
            }
            $stmt .= PdoDb::formatField(PdoDb::addTbPrefix($table[0]));
            if (!empty($table[1])) {
                $stmt .= " " . PdoDb::formatField($table[1]);
            }
        }
        return $stmt;
    }
}
