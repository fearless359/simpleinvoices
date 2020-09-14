<?php

namespace Inc\Claz;

/**
 * WhereClause class is a collection of <b>WhereItem</b> objects specifying the
 * selection criteria for PDO requests.
 * @author Rich
 */
class WhereClause
{
    private array $whereItems;
    private int $parenCnt;
    private int $tokenCnt;
    private bool $endOfClause;
    private string $clause;

    /**
     * class constructor
     * Instantiates an object of the <b>WhereClause</b> class.
     * @param WhereClause|WhereItem|null $whereItem (Optional) If set, will add to this newly instantiated object.
     * @throws PdoDbException
     */
    public function __construct($whereItem = null)
    {
        $this->whereItems = [];
        $this->parenCnt = 0;
        $this->tokenCnt = 0;
        $this->clause = '';
        if (isset($whereItem)) {
            $this->addItem($whereItem);
        }
    }

    /**
     * Check to see if there object is empty.
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->whereItems);
    }

    /**
     * Check to see if we hit the end of the clause for all items.
     * @return bool
     */
    public function isEndOfClause(): bool
    {
        return $this->endOfClause;
    }

    /**
     * getter for $tokenCnt.
     * Note that the current token count value has <b>NOT</b> been used to
     * make a unique token.
     * @return int Current token count value.
     */
    public function getTokenCnt(): int
    {
        return $this->tokenCnt;
    }

    /**
     * Add a <b>WhereItem</b> object to the <i>WHERE</i> clause.
     * @param WhereClause|WhereItem|OnClause|OnItem $whereItem
     * @throws PdoDbException If end of clause shows out of balance parenthesis.
     */
    public function addItem($whereItem): void
    {
        if (is_a($whereItem, 'Inc\Claz\WhereClause') || is_a($whereItem, 'Inc\Claz\OnClause')) {
            foreach($whereItem->whereItems as $wi) {
                $this->addItem($wi);
            }
        } else {
            $this->whereItems[] = $whereItem;
            $this->parenCnt += $whereItem->parenCount();
            $this->endOfClause = $whereItem->endOfClause();
        }
        if ($this->endOfClause && $this->parenCnt != 0) {
            throw new PdoDbException("WhereClause - addItem(): Invalid clause termination. There are too " .
                ($whereItem->parenCount() > 0 ? "few " : "many ") . "closing parenthesis.");
        }
    }

    /**
     * Add a <b>WhereItem</b> that performs an equality check.
     * @param string $field Table column for the left side of the test.
     * @param array|string|int|DbField $value Constant or <b>DbField</b> for the right side of the test.
     * @param string $connector (Optional) <b>AND</b> or <b>OR</b> connector if this
     *        is not that last statement in the <b>WHERE</b> clause.
     * @throws PdoDbException
     */
    public function addSimpleItem(string $field, $value, string $connector = ""): void
    {
        $this->addItem(new WhereItem(false, $field, "=", $value, false, $connector));
    }

    /**
     * Class property getter
     * return int $parenCnt;
     */
    public function getParenCnt(): int
    {
        return $this->parenCnt;
    }

    /**
     * Build the <b>WHERE</b> clause to append to the request.
     * @param array|null &$keyPairs Array of PDO token and value pairs to bind to the PDO statement.
     *              Note that if not set, this array is initialized to empty by this method.
     * @param bool $firstTime true if first time in this build, false if recursive call.
     * @return string WHERE statement.
     * @throws PdoDbException if specified parenthesis have not been properly paired.
     */
    public function build(?array &$keyPairs, bool $firstTime = true): string
    {
        if (!isset($keyPairs)) {
            $keyPairs = [];
        }

        if (empty($this->whereItems)) {
            return '';
        }

        if ($this->parenCnt != 0) {
            throw new PdoDbException("WhereClause - build(): Parenthesis mismatch.");
        }

        if ($firstTime) {
            $this->clause = "WHERE ";
        }

        foreach ($this->whereItems as $whereItem) {
            if ($whereItem instanceof WhereClause || $whereItem instanceof OnClause) {
                $this->clause = $this->buildWhereClause($whereItem, $this->tokenCnt, $keyPairs);
            } elseif (!$whereItem instanceof WhereItem && !$whereItem instanceof OnItem) {
                // This can't happen unless the add logic validation is broken. The test is performed
                // to cause logic to treat the object as an instance of the class.
                throw new PdoDbException("WhereClause - build(): Invalid object type found in class array. " .
                    "Must be an instance of the WhereItem or OnItem class.");
            } else {
                $this->clause .= $whereItem->build($this->tokenCnt, $keyPairs);
            }
        }
        return $this->clause;
    }

    /**
     * @param array|WhereClause|OnClause $whereClause
     * @param int &$tokenCnt
     * @param array &$keyPairs
     * @return string
     */
    private function buildWhereClause($whereClause, int &$tokenCnt, array &$keyPairs): string
    {
        /**
         * @var WhereItem $whereItem
         */
        foreach ($whereClause as $whereItem) {
            if (is_array($whereItem)) {
                $this->clause .= $this->buildWhereClause($whereItem, $tokenCnt, $keyPairs);
            } else {
                $this->clause .= $whereItem->build($tokenCnt, $keyPairs);
            }
        }
        return $this->clause;
    }
}
