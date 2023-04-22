<?php

namespace Inc\Claz;

/**
 * Class for a single test item in the <b>HAVING</b> clause.
 * @author Rich Rowley
 */
class Having
{
    private const OPERATORS = '/^(=|<>|<|>|<=|>=|BETWEEN)$/';
    private const CONNECTORS = '/^(OR|AND)$/';

    private bool $leftParen;
    private string $field;
    private string $operator;
    /**
     * @var DbField|int|string
     */
    private $value;
    private string $connector;
    private bool $rightParen;

    /**
     * Class constructor
     * @param bool $leftParen (Optional) <b>true</b> if a left parenthesis should be placed before this
     *        statement; else <b>false</b> (default) if no left parenthesis is to be added.
     * @param string|DbField|FunctionStmt $field
     * @param string $operator
     * @param DbField|number|array|string $value Can be a string, number or an array as needed by the specified <b>$operator</b>.
     * @param string $connector (Optional) If specified, should be set to <b>AND</b> or <b>OR</b>. If
     *        not specified, it will be set automatically to <b>AND</b> if a subsequent
     *        criterion is added.
     * @param bool $rightParen (Optional) <b>true</b> if a right parenthesis should be placed after the
     *        <i>$value</i> parameter; else <b>false</b> (default) if no right parenthesis is to be added.
     * @throws PdoDbException
     */
    public function __construct(bool $leftParen, $field, string $operator, $value, bool $rightParen=false, string $connector="")
    {
        $this->leftParen = $leftParen;
        $this->rightParen = $rightParen;

        $this->field = "";
        if (!empty($field)) {
            if (is_string($field) || is_a($field, "Inc\Claz\DbField") || is_a($field, "Inc\Clz\FunctionStmt")) {
                $this->field = $field;
            }
        }

        if (empty($this->field)) {
            error_log("Having - _construct(): field parameter type is invalid. field - " . print_r($field, true));
            throw new PdoDbException("Having - Invalid field parameter. See error log for details.");
        }

        if (!preg_match(self::OPERATORS, $operator)) {
            error_log(print_r(debug_backtrace(), true));
            error_log("Having - Invalid. operator - " . print_r($operator, true));
            throw new PdoDbException("Having - operator is invalid.");
        }
        $this->operator = $operator;

        if (!isset($value)) {
            error_log("Having - value parameter is invalid. value - " . print_r($value, true));
            throw new PdoDbException("Having - value is invalid. See error log for details.");
        } elseif ($operator == "BETWEEN" && !is_array($value)) {
            error_log("Having - value parameter must be an array for BETWEEN operator.");
            throw new PdoDbException("Having - value is invalid. See error log for details.");
        } elseif ($operator != "BETWEEN" && !is_a($value, "Inc\Claz\DbField") && !is_string($value) && !is_integer($value)) {
            error_log("Having - value parameter must be a string or DbField object for specified operator.");
            throw new PdoDbException("Having - value is invalid. See error log for details.");
        }
        $this->value = $value;

        $this->setConnector($connector);
    }

    /**
     * Builds the formatted selection criterion for this object.
     * @return string Formatted <b>HAVING</b> clause component for this criterion.
     */
    public function build(): string
    {
        $having = $this->leftParen ? "(" : "";

        if (is_a($this->field, "Inc\Claz\FunctionStmt")) {
            $having .= $this->field->build();
        } elseif (is_a($this->field, "Inc\Claz\DbField")) {
            $having .= $this->field->genParm();
        } else {
            $having .= $this->field;
        }

        $having .= " " . $this->operator . " ";

        if (is_array($this->value)) {
            $having .= (empty($this->value[0]) ? "''" : "'" . $this->value[0] . "'") . " AND " .
                (empty($this->value[1]) ? "''" : "'" . $this->value[1] . "'");
        } else {
            $having .= empty($this->value) ? "''" : "'" . $this->value . "'";
        }

        $having .= $this->rightParen ? ")" : "";
        $having .= empty($this->connector) ? "" : " " . $this->connector;

        return $having;
    }

    public function getField(): string
    {
        return $this->field;
    }

    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @return DbField|int|string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getConnector(): string
    {
        return $this->connector;
    }

    /**
     * Test if left paren specified.
     * @return bool <b>true</b> if paren is required; <b>false</b> if not.
     */
    public function isLeftParen(): bool
    {
        return $this->leftParen;
    }

    /**
     * Test if right paren specified.
     * @return bool <b>true</b> if paren is required; <b>false</b> if not.
     */
    public function isRightParen(): bool
    {
        return $this->rightParen;
    }

    /**
     * Set the connector to a specified value.
     * @param string $connector Valid connector, <b>OR</b>, or <b>AND</b>.
     * @return  void
     * @throws PdoDbException
     */
    public function setConnector(string $connector)
    {
        $this->connector = $connector;
        if (!empty($connector)) {
            if (!preg_match(self::CONNECTORS, $connector)) {
                error_log(print_r(debug_backtrace(), true));
                error_log("Having setConnector() - Invalid connector - " . print_r($connector, true));
                throw new PdoDbException("Having setConnector() - Connector is invalid.");
            }
        }
    }
}
