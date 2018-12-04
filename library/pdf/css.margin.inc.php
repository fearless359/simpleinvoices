<?php

require_once(HTML2PS_DIR . 'value.margin.class.php');

class CSSMargin extends CSSPropertyHandler
{
    var $default_value;

    public function __construct()
    {
        $this->default_value = $this->parse("0");
        parent::__construct(false, false);
    }

    public function default_value()
    {
        return $this->default_value->copy();
    }

    public static function parse_in($value)
    {
        $values = explode(" ", trim($value));

        switch (count($values)) {
            case 1:
                $v1 = $values[0];
                return array($v1, $v1, $v1, $v1);
            case 2:
                $v1 = $values[0];
                $v2 = $values[1];
                return array($v1, $v2, $v1, $v2);
            case 3:
                $v1 = $values[0];
                $v2 = $values[1];
                $v3 = $values[2];
                return array($v1, $v2, $v3, $v2);
            case 4:
                $v1 = $values[0];
                $v2 = $values[1];
                $v3 = $values[2];
                $v4 = $values[3];
                return array($v1, $v2, $v3, $v4);
            default:
                // We newer should get there, because 'margin' value can contain from 1 to 4 widths
                return array(0, 0, 0, 0);
        }
    }

    function parse($value)
    {
        if ($value === 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }

        $value = MarginValue::init($this->parse_in($value));
        return $value;
    }

    public static function getPropertyCode()
    {
        return CSS_MARGIN;
    }

    public static function getPropertyName()
    {
        return 'margin';
    }
}

class CSSMarginTop extends CSSSubFieldProperty
{
    public function parse($value)
    {
        if ($value === 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }
        return MarginSideValue::init($value);
    }

    public static function getPropertyCode()
    {
        return CSS_MARGIN_TOP;
    }

    public static function getPropertyName()
    {
        return 'margin-top';
    }

    public static function default_value()
    {
    }

}

class CSSMarginRight extends CSSSubFieldProperty
{
    public static function parse($value)
    {
        if ($value === 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }
        return MarginSideValue::init($value);
    }

    public static function getPropertyCode()
    {
        return CSS_MARGIN_RIGHT;
    }

    public static function getPropertyName()
    {
        return 'margin-right';
    }

    public static function default_value()
    {
    }

}

class CSSMarginLeft extends CSSSubFieldProperty
{
    public static function parse($value)
    {
        if ($value === 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }
        return MarginSideValue::init($value);
    }

    public static function getPropertyCode()
    {
        return CSS_MARGIN_LEFT;
    }

    public static function getPropertyName()
    {
        return 'margin-left';
    }

    public static function default_value()
    {
    }
}

class CSSMarginBottom extends CSSSubFieldProperty
{
    public static function parse($value)
    {
        if ($value === 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }
        return MarginSideValue::init($value);
    }

    public static function getPropertyCode()
    {
        return CSS_MARGIN_BOTTOM;
    }

    public static function getPropertyName()
    {
        return 'margin-bottom';
    }

    public static function default_value()
    {
    }

}

$mh = new CSSMargin;
CSS::register_css_property($mh);
$css_margin_inc_reg1 = new CSSMarginLeft($mh, 'left');
CSS::register_css_property($css_margin_inc_reg1);
$css_margin_inc_reg2 = new CSSMarginRight($mh, 'right');
CSS::register_css_property($css_margin_inc_reg2);
$css_margin_inc_reg3 = new CSSMarginTop($mh, 'top');
CSS::register_css_property($css_margin_inc_reg3);
$css_margin_inc_reg4 = new CSSMarginBottom($mh, 'bottom');
CSS::register_css_property($css_margin_inc_reg4);
