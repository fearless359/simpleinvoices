<?php
// $Header: /cvsroot/html2ps/css.min-width.inc.php,v 1.1 2006/09/07 18:38:14 Konstantin Exp $

class CSSMinWidth extends CSSSubFieldProperty
{
    public function __construct(&$owner, $field)
    {
        parent::__construct($owner, $field);
    }

    public static function getPropertyCode()
    {
        return CSS_MIN_WIDTH;
    }

    public static function getPropertyName()
    {
        return 'min-width';
    }

    public static function parse($value)
    {
        if ($value == 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }

        return Value::fromString($value);
    }

    public static function default_value()
    {
    }

}
