<?php

namespace Inc\Claz;

/**
 * Class FunctionStmt
 * @package Inc\Claz
 */
class FunctionStmt
{
    private const OPERATORS = '/^(\-|\+|\*|\/)$/';

    /**
     * @var string|null
     */
    private $function;
    /**
     * @var DbField|string
     */
    private $parameter;
    private string $alias;
    private array $parts;

    /**
     * Class constructor.
     * @param string|null $function Function to be performed. Can be set to <b>null</b> if necessary.
     * @param DbField|string $parameter The field the operation is performed on. Can be a string such as
     *        "SUM(amount)" or "amount" or can be a DbField.
     * @param string $alias (Optional) Name to assign to the function result.
     */
    public function __construct(?string $function, $parameter, string $alias = "")
    {
        $this->function = $function;
        $this->parameter = $parameter;
        $this->alias = $alias;
        $this->parts = [];
    }

    /**
     * Add another part of the function.
     * @param string $operator Math operator value is <b>+</b>, <b>-</b>, <b>*</b> or <b>/</b>.
     * @param DbField|object|string $part
     * @throws PdoDbException if an invalid <b>$operator</b> is specified.
     */
    public function addPart(string $operator, $part): void
    {
        if (!preg_match(self::OPERATORS, $operator)) {
            $str = "FunctionStmt - addPart(): Invalid operator, $operator.";
            error_log($str);
            throw new PdoDbException($str);
        }

        $this->parts[] = [$operator, $part];
    }

    /**
     * Build function string from specified parameter.
     * @return string Function string.
     */
    public function build(): string
    {
        if (is_a($this->parameter, "Inc\Claz\DbField")) {
            $parm = $this->parameter->genParm();
        } else {
            $parm = $this->parameter;
        }

        if (empty($this->function)) {
            $stmt = $parm;
        } else {
            $stmt = $this->function . "(" . $parm . ")";
        }
        if (!empty($this->parts)) {
            foreach ($this->parts as $part) {
                if (is_a($part[1], "Inc\Claz\DbField")) {
                    $parm = $part[1]->genParm();
                } else {
                    $parm = $part[1];
                }
                $stmt = "(" . $stmt . ") " . $part[0] . " " . $parm;
            }
        }
        if (!empty($this->alias)) {
            $stmt = "(" . $stmt . ") AS " . $this->alias;
        }
        return $stmt;
    }
}
