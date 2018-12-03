<?php
// $Header: /cvsroot/html2ps/css.border.right.color.inc.php,v 1.1 2006/09/07 18:38:13 Konstantin Exp $

class CSSBorderRightColor extends CSSSubProperty
{
    public function __construct(&$owner)
    {
        parent::__construct($owner);
    }

    public function setValue(&$owner_value, &$value)
    {
        $owner_value->right->setColor($value);
    }

    public function &getValue(&$owner_value)
    {
        return $owner_value->right->color->copy();
    }

    public static function getPropertyCode()
    {
        return CSS_BORDER_RIGHT_COLOR;
    }

    public static function getPropertyName()
    {
        return 'border-right-color';
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
