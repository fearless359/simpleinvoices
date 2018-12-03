<?php
// $Header: /cvsroot/html2ps/css.border.left.style.inc.php,v 1.1 2006/09/07 18:38:13 Konstantin Exp $

class CSSBorderLeftStyle extends CSSSubProperty
{
    public function __construct(&$owner)
    {
        parent::__construct($owner);
    }

    public function setValue(&$owner_value, &$value)
    {
        $owner_value->left->style = $value;
    }

    public function &getValue(&$owner_value)
    {
        return $owner_value->left->style;
    }

    public static function getPropertyCode()
    {
        return CSS_BORDER_LEFT_STYLE;
    }

    public static function getPropertyName()
    {
        return 'border-left-style';
    }

    public static function parse($value)
    {
        if ($value == 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }

        return CSSBorderStyle::parse_style($value);
    }

    public static function default_value()
    {
    }

}
