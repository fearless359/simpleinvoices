<?php
// $Header: /cvsroot/html2ps/css.border.top.color.inc.php,v 1.1 2006/09/07 18:38:13 Konstantin Exp $

class CSSBorderTopColor extends CSSSubProperty
{
    public function __construct(&$owner)
    {
        parent::__construct($owner);
    }

    public function setValue(&$owner_value, &$value)
    {
        $owner_value->top->setColor($value);
    }

    public function &getValue(&$owner_value)
    {
        return $owner_value->top->color->copy();
    }

    public static function getPropertyCode()
    {
        return CSS_BORDER_TOP_COLOR;
    }

    public static function getPropertyName()
    {
        return 'border-top-color';
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
