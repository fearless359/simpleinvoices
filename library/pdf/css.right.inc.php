<?php
// $Header: /cvsroot/html2ps/css.right.inc.php,v 1.6 2006/11/11 13:43:52 Konstantin Exp $

require_once(HTML2PS_DIR . 'value.right.php');

class CSSRight extends CSSPropertyHandler
{
    function __construct()
    {
        parent::__construct(false, false);
        $this->_autoValue = ValueRight::fromString('auto');
    }

    function _getAutoValue()
    {
        return $this->_autoValue->copy();
    }

    function default_value()
    {
        return $this->_getAutoValue();
    }

    public static function parse($value)
    {
        return ValueRight::fromString($value);
    }

    public static function getPropertyCode()
    {
        return CSS_RIGHT;
    }

    public static function getPropertyName()
    {
        return 'right';
    }
}

$css_right_inc_reg1 = new CSSRight();
CSS::register_css_property($css_right_inc_reg1);
