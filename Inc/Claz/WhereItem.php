<?php

namespace Inc\Claz;

/**
 * Class for a single test item in the <b>WHERE</b> clause.
 * @author Rich Rowley
 */
class WhereItem
{
    private const CONNECTORS = '/^(AND|OR)$/';
    private const OPERATORS = '/^(=|<>|<|>|<=|>=|<=>|BETWEEN|LIKE|IN|REGEXP|IS NULL|IS NOT NULL|REGEXP)$/';

    private bool $closeParen;
    private string $connector;
    private string $field;
    private bool $openParen;
    private string $operator;
    private string $token;
    /**
     * @var array|DbField|int|string
     */
    private $value;

    /**
     * Class constructor
     * @param bool $openParen Set to <b>true</b> if an opening parenthesis should be
     *        inserted before this term; otherwise set to <b>false</b>.
     * @param string $field The actual name of the field (column) in the table. This is
     *        a required parameter and <b>MUST</b> exist in the table.
     * @param string $operator Valid SQL comparison operator to the <b>$field</b> record
     *        content test against the <b>$value</b> parameter. Allowed operators:
     *          <b>=</b>, <b><></b>, <b><</b>, <b>></b>, <b><=</b> and <b>>=</b>,
     *          <b>BETWEEN</b>, <b>LIKE</b>, <b>IN</b>, <b>IS</b>, <b>IS NOT</b>, <b>REGEXP</b>
     * @param array|int|string|DbField $value Value to use in the test. Note for <b>BETWEEN</b> this will be: <b>array(beginVal,endVal)</b>.
     * @param bool $closeParen Set to <b>true</b> if a closing parenthesis should be
     *        inserted after this term; otherwise set to <b>false</b>.
     * @param string $connector The "AND" or "OR" connector if additional terms will be
     *        clause. Optional parameter.
     * @throws PdoDbException If an invalid operator or connector is found.
     */
    public function __construct(bool $openParen, string $field, string $operator, $value, bool $closeParen, string $connector = "")
    {
        $this->openParen = $openParen;
        $this->field = $field;
        $this->operator = strtoupper($operator);
        $this->connector = strtoupper($connector);
        $this->closeParen = $closeParen;

        if (!preg_match(self::OPERATORS, $this->operator)) {
            throw new PdoDbException("WhereItem - Invalid operator, $this->operator, specified.");
        }

        if (!empty($this->connector) && !preg_match(self::CONNECTORS, $this->connector)) {
            throw new PdoDbException("WhereItem - Invalid connector, $this->connector, specified. Must be \"AND\" or \"OR\".");
        }

        switch ($this->operator) {
            case 'BETWEEN':
                if (!is_array($value) || count($value) != 2) {
                    throw new PdoDbException("WhereItem - Invalid value for BETWEEN operator. Must be an array of two elements.");
                }
                $this->value = $value;
                break;

            case 'IN':
                if (!is_array($value)) {
                    throw new PdoDbException("WhereItem - Invalid value for IN operator. Must be an array.");
                }
                $this->value = $value;
                break;

            case 'IS NULL':
            case 'IS NOT NULL':
                if (!empty($value)) {
                    throw new PdoDbException("WhereItem - Value must be blank for '$this->operator' operator'.");
                }
                $this->value = strtoupper($value);
                break;

            default:
                $this->value = $value;
                break;
        }
        $this->token = ':' . $field; // Made unique in build.
    }

    /**
     * Builds the formatted selection criterion for this object.
     * @param int $tokenCnt Number of tokens processed in this build.
     *        Note this parameter is <i>passed by reference</i> so it's updated
     *        value will be returned in it. Set it to <b>0</b> on the initial call and
     *        return the updated variable in subsequent calls.
     * @param array $keyPairs Associative array indexed by the PDO <i>token</i> that
     *        references the value of the token. Example: $keyParis[<b>':domain_id'</b>] with
     *        a value of <b>1</b>.
     * @return string Formatted <b>WHERE</b> clause component for this criterion.
     */
    public function build(int &$tokenCnt, array &$keyPairs): string
    {
        $item = '';
        if ($this->openParen) {
            $item .= '(';
        }
        $field = PdoDb::formatField($this->field);
        $item .= "$field $this->operator ";
        switch ($this->operator) {
            case 'BETWEEN':
                $tk1 = PdoDb::makeToken($this->token, $tokenCnt);
                $tk2 = PdoDb::makeToken($this->token, $tokenCnt);
                $item .= "$tk1 AND $tk2";
                $keyPairs[$tk1] = $this->value[0];
                $keyPairs[$tk2] = $this->value[1];
                break;

            case 'IN':
                // Make array if not one already
                $item .= '(';
                for ($idx = 0; $idx < count($this->value); $idx++) {
                    $tk = PdoDb::makeToken($this->token, $tokenCnt);
                    $item .= $idx == 0 ? '' : ', ';
                    $item .= $tk;
                    $keyPairs[$tk] = $this->value[$idx];
                }
                $item .= ')';
                break;

            case 'REGEXP':
                $item = '(' . $item;
                $tk = PdoDb::makeToken($this->token, $tokenCnt);
                $item .= $tk . ' ';
                $keyPairs[$tk] = $this->value;
                $item .= ')';
                break;

            case 'IS NULL':
            case 'IS NOT NULL':
                // No token for this operator.
                break;

            default:
                if (is_a($this->value, "Inc\Claz\DbField")) {
                    $item .= $this->value->genParm();
                } else {
                    $tk = PdoDb::makeToken($this->token, $tokenCnt);
                    $item .= $tk . ' ';
                    $keyPairs[$tk] = $this->value;
                }
                break;
        }

        if ($this->closeParen) {
            $item .= ') ';
        }

        $item .= empty($this->connector) ? '' : ' ' . $this->connector . ' ';

        return $item;
    }

    /**
     * Calculates unmatched parenthesis in this object.
     * @return int Count of unmatched parenthesis in this object. A positive result
     *         is count of unmatched opening parenthesis, a negative result is count of
     *         unmatched closing parenthesis and a result of 0 means all parenthesis if
     *         any are matched.
     */
    public function parenCount(): int
    {
        $cnt = 0;
        if ($this->openParen) {
            $cnt++;
        }
        if ($this->closeParen) {
            $cnt--;
        }
        return $cnt;
    }

    /**
     * Flags the end of items for the <b>WHERE</b> clause.
     * @return bool true if end of clause, false if not (AND or OR specified).
     */
    public function endOfClause(): bool
    {
        return empty($this->connector);
    }
}
