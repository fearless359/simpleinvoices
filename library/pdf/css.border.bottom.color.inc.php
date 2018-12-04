<?php
// $Header: /cvsroot/html2ps/css.border.bottom.color.inc.php,v 1.2 2006/11/16 03:32:56 Konstantin Exp $

class CSSBorderBottomColor extends CSSSubProperty
{
    public function __construct(&$owner)
    {
        parent::__construct($owner);
    }

    public function setValue(&$owner_value, &$value)
    {
        $owner_value->bottom->setColor($value);
    }

    public function &getValue(&$owner_value)
    {
        $value = $owner_value->bottom->color->copy();
        return $value;
    }

    public static function getPropertyCode()
    {
        return CSS_BORDER_BOTTOM_COLOR;
    }

    public static function getPropertyName()
    {
        return 'border-bottom-color';
    }

    public static function parse($value)
    {
        if ($value == 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }

        return parse_color_declaration($value);
    }

    public static function default_value()
    {
    }

}
