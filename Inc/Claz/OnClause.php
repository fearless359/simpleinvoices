<?php

namespace Inc\Claz;

/**
 * Class OnClause
 * @package Inc\Claz
 */
class OnClause extends WhereClause
{

    /**
     * class constructor
     * @param OnItem|null $onItem If set, will add to this newly instantiated object.
     * @throws PdoDbException
     */
    public function __construct(?OnItem $onItem = null)
    {
        parent::__construct($onItem);
    }

    /**
     * Add an <b>OnItem</b> object constructed from simple parameters.
     * @param string $field Field (aka column) of table to be joined or available in the scope
     *        of fields from tables in the join statement.
     * @param array|int|string|DbField $value Value to use in the test. This can be a constant or a field in
     *        the table being joined to. Note that if this is a table field, the <i>DbField</i>
     *        class should be used to render it. Ex: obj->addSimpleItem(iv.id, new DbField(ii.id)).
     * @param string $connector The "AND" or "OR" connector if additional terms will be
     *        clause. Optional parameter.
     * @throws PdoDbException If an invalid operator or connector is found.
     */
    public function addSimpleItem(string $field, $value, string $connector = "")
    {
        try {
            parent::addSimpleItem($field, $value, $connector);
        } catch (PdoDbException $pde) {
            throw new PdoDbException(preg_replace('/WhereClause/', 'OnClause', $pde->getMessage()));
        }
    }

    /**
     * Add a <b>OnItem</b> object to the <i>ON</i> clause.
     * @param OnClause|OnItem|WhereClause|WhereItem $whereItem
     * @throws PdoDbException If end of clause shows out of balance parenthesis.
     */
    public function addItem($whereItem)
    {
        try {
            parent::addItem($whereItem);
        } catch (PdoDbException $pde) {
            throw new PdoDbException(preg_replace('/WhereClause/', 'OnClause', $pde->getMessage()));
        }
    }

    /**
     * Build the <b>ON</b> clause to append to the request.
     * @param array|null &$keyPairs Array of PDO token and value pairs to bind to the PDO statement.
     *              Note that if not set, this array is initialized to empty by this method.
     * @param bool $firstTime true if first time in this build, false if recursive call.
     * @return string ON clause string
     * @throws PdoDbException if specified parenthesis have not been properly paired.
     */
    public function build(?array &$keyPairs, bool $firstTime = true): string
    {

        try {
            $pattern = '/^WHERE /';
            $clause = preg_replace($pattern, 'ON ', parent::build($keyPairs, $firstTime));
        } catch (PdoDbException $pde) {
            throw new PdoDbException(preg_replace('/WhereClause/', 'OnClause', $pde->getMessage()));
        }
        return $clause;
    }
}
