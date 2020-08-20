<?php

namespace Inc\Claz;

/**
 * Class When
 * @package Inc\Claz
 */
class When
{
    private const OPERATORS = '/^(=|!=|<>|<|>|<=|>=)$/';

    private string $field;
    private string $operator;
    private string $value;
    private string $result;

    /**
     * Class constructor
     * @param string $field to place left of the operator.
     * @param string $operator Limited to <b>=</b>, <b>!=</b>, <b>&lt;=</b>, <b>&lt;&gt;</b>, <b>&lt;</b>,
     *        <b>&gt;</b> and <b>&gt;=</b>.
     * @param string $value Value or field to place on the right side of the operator.
     * @param string $result Value to assign if the test is true.
     * @throws PdoDbException If the operator is not on of those currently supported.
     */
    public function __construct(string $field, string $operator, string $value, string $result)
    {
        // @formatter:off
        $this->field    = $field;
        $this->operator = $operator;
        $this->value    = $value;
        $this->result   = $result;
        // @formatter:on

        if (!preg_match(self::OPERATORS, $this->operator)) {
            throw new PdoDbException("When - Invalid operator, $this->operator, specified.");
        }
    }

    /**
     * Build this clause.
     * @return string <b>WHEN</b> clause rendered from values in this object.
     */
    public function build(): string
    {
        return "WHEN {$this->field} {$this->operator} {$this->value} THEN '{$this->result}' ";
    }
}
