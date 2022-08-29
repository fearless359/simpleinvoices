<?php

namespace Inc\Claz;

/**
 * OrderBy class
 * @author Rich
 */
class OrderBy
{
    private array $orderByFields;

    /**
     * Class constructor.
     * @param string $field Primary field to order data by.
     * @param string $order Order <b>A</b> ascending, <b>D</b> descending.
     *        Defaults to ascending if not specified.
     * @throws PdoDbException object if an invalid value is specified for the
     *         <b>order</b> parameter.
     */
    public function __construct(string $field = "", string $order = 'A')
    {
        $this->orderByFields = [];
        if (!empty($field)) {
            $this->addField($field, $order);
        }
    }

    /**
     * Test object to see if it is empty (no values added yet).
     * @return bool true if empty; false if not.
     */
    public function isEmpty(): bool
    {
        return empty($this->orderByFields);
    }

    /**
     * Add a field and order attribute.
     * @param array|string $field Either an <i>array</i> or <i>string</i>.
     *        The following forms are valid:
     *          <i>string</i> - A <i>field name</i> to be added to the collection
     *                          of ordered items with the specified <b>$order</b>.
     *          <i>array</i>  - An array of <i>field names</i> or of <i>arrays</i>.<br/>
     *                          If an <i>array of field names</i>, each <i>field name</i> is added
     *                          to the list of ordered items with default order of <b>ASC</b>.<br/>.
     *                          If an <i>array of arrays</i>, each element array can have <i>one</i>
     *                          or <i>two</i> elements. Element arrays of <i>two</dimensions contains
     *                          a <i>field name</i> for the first index and a sort order value in the
     *                          second element. Valid sort order values are: <b>A</b>, <b>ASC</b>, <b>D</b>
     *                          or <b>DESC</b>. Element arrays of <i>one</i> dimension contains a
     *                          <i>field name</i> and will use the value specified in the <b>$order</b>
     *                          parameter field for sorting.
     * @param string $order Order <b>A</b> ascending, <b>D</b> descending. Defaults to <b>A</b>.
     * @throws PdoDbException if either parameter does not contain the form and values specified for them.
     */
    public function addField($field, string $order = 'A'): void
    {
        $lclOrder = strtoupper($order);
        if (!preg_match('/^(A|D|ASC|DESC)$/', $lclOrder)) {
            $str = "OrderBy - addField(): Invalid order, $lclOrder, specified.";
            error_log($str);
            throw new PdoDbException($str);
        }

        $lclOrder = preg_match('/^(A|ASC)$/', $lclOrder) ? 'ASC' : 'DESC';

        if (is_array($field)) {
            foreach ($field as $item) {
                if (is_array($item)) {
                    if (count($item) == 2 && is_string($item[0]) &&
                        ($item[1] == 'A' || $item[1] == 'D')) {
                        $this->orderByFields[] = [$item[0], $item[1] == 'A' ? 'ASC' : 'DESC'];
                    } elseif (count($item) == 1 && is_string($item[0])) {
                        $this->orderByFields[] = [$item[0], $lclOrder];
                    } else {
                        $str = "OrderBy - addField(): Invalid array content. ";
                        if (count($item) == 2) {
                            $str .= "field name: $item[0], order: $item[1]";
                        } elseif (count($item) == 1) {
                            $str .= "field name: $item[0]";
                        } else {
                            $str .= "Too many elements. Dimensions: " . count($item);
                        }
                        error_log($str);
                        throw new PdoDbException($str);
                    }
                } else {
                    $this->orderByFields[] = [$field, $lclOrder];
                }
            }
        } elseif (is_string($field)) {
            $item = [$field, $lclOrder];
            $this->orderByFields[] = $item;
        } else {
            $str = "OrderBy - addField(): Invalid <b>\$field</b> type.";
            error_log($str);
            throw new PdoDbException($str);
        }
    }

    /**
     * Build the <b>ORDER BY</b> statement.
     * @return string Formatted <b>ORDER by</b> string.
     */
    public function build(): string
    {
        $orderBy = '';
        if (!empty($this->orderByFields)) {
            foreach ($this->orderByFields as $items) {
                if (empty($orderBy)) {
                    $orderBy = "ORDER BY ";
                } else {
                    $orderBy .= ', ';
                }
                $orderBy .= PdoDb::formatField($items[0]) . ' ' . $items[1];
            }
        }
        return $orderBy;
    }
}
