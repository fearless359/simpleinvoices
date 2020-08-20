<?php

namespace Inc\Claz;

/**
 * Having class
 * @author Rich
 */
class Havings
{
    private array $havings;

    /**
     * Construct <b>Havings</b> object with initial <b>Having</b> element.
     * @param string $field
     * @param string $operator
     * @param mixed $value Can be a string, DbField object or an array.
     * @param string (Optional) If specified, should be set to <b>AND</b> or <b>OR</b>. If
     *        not specified, it will be set automatically to <b>AND</b> if a subsequent
     *        criterion is added.
     * @throws PdoDbException
     */
    public function __construct(string $field = "", string $operator = "", string $value = "", string $connector = "")
    {
        $this->havings = [];
        if (!empty($field) && !empty($operator) && !empty($value)) {
            $having = new Having(false, $field, $operator, $value, false, $connector);
            $this->havings[] = $having;
        }
    }

    /**
     * Add a <b>Having</b> object
     * @param bool $leftParen <b>true</b> if left parenthesis should be included; <b>false</b> if not.
     * @param string $field Field name to use.
     * @param string $operator
     * @param DbField|array|number $value Can be a any data type needed by the specified <b>$operator</b>.
     * @param bool $rightParen <b>true</b> if right parenthesis should be included; </b>false</b> if not.
     * @param string (Optional) If specified, should be set to <b>AND</b> or <b>OR</b>. If
     *        not specified, it will be set automatically to <b>AND</b> if a subsequent
     *        criterion is added.
     * @throws PdoDbException
     */
    public function add(bool $leftParen, string $field, string $operator, $value,
                        bool $rightParen=false, string $connector=""): void
    {
        $this->addDefaultConnector();
        $this->havings[] = new Having($leftParen, $field, $operator, $value, $rightParen, $connector);
    }

    /**
     * Add another <b>Having</b> object to this clause.
     * @param string $field
     * @param string $operator
     * @param DbField|array|number|string $value Can be a string, DbField object or an array.
     * @param string (Optional) If specified, should be set to <b>AND</b> or <b>OR</b>. If
     *        not specified, it will be set automatically to <b>AND</b> if a subsequent
     *        criterion is added.
     * @throws PdoDbException
     */
    public function addSimple(string $field, string $operator, $value, string $connector=""): void
    {
        $this->addDefaultConnector();
        $having = new Having(false, $field, $operator, $value, false, $connector);
        $this->havings[] = $having;
    }

    /**
     * Add <b>Havings</b> or <b>Having</b> object content to this object.
     * @param Havings|Having $havings Object with values to add.
     * @throws PdoDbException Invalid parameter type
     */
    public function addHavings($havings): void
    {
        $this->addDefaultConnector();
        if (is_a($havings, "Inc\Claz\Having")) {
            $this->add(false, $havings->getField(), $havings->getOperator(), $havings->getValue(), false);
        } elseif (is_a($havings, "Inc\Claz\Havings")) {
            $this->havings = array_merge($this->havings, $havings->getHavings());
        } else {
            error_log("Havings addHavings() - Invalid parameters type specified: " . print_r($havings, true));
            throw new PdoDbException("Havings addHavings() - Invalid parameters type. See error log for details.");
        }
    }

    /**
     * If the last <b>Having</b> object added does not have a connector, add
     * a default, <b>"AND"</b> connector.
     */
    private function addDefaultConnector(): void
    {
        $ndx = count($this->havings) - 1;
        if ($ndx >= 0) {
            $having = $this->havings[$ndx];
            $result = $having->getConnector();
            if (empty($result)) {
                $having->setConnector("AND");
                $this->havings[$ndx] = $having;
            }
        }
    }

    /**
     * Builds the formatted <b>HAVING</b> statement for collected <i>Having</i> objects.
     * @return string Formatted <b>HAVING</b> clause component for collected criteria.
     */
    public function build(): string
    {
        if (empty($this->havings)) {
            return "";
        }

        $result = "HAVING";
        foreach ($this->havings as $having) {
            $result .= " " . $having->build();
        }

        return $result;
    }

    /**
     * getter for class property.
     * @return array Array of <b>Having</b> objects assigned to this this object.
     */
    protected function getHavings(): array
    {
        return $this->havings;
    }
}
