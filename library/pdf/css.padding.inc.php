<?php

require_once(HTML2PS_DIR . 'value.padding.class.php');

class CSSPadding extends CSSPropertyHandler
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
                // We newer should get there, because 'padding' value can contain from 1 to 4 widths
                return array(0, 0, 0, 0);
        }
    }

    public function parse($string)
    {
        if ($string === 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }

        $padding = PaddingValue::init($this->parse_in($string));

        return $padding;
    }

    public static function getPropertyCode()
    {
        return CSS_PADDING;
    }

    public static function getPropertyName()
    {
        return 'padding';
    }
}

class CSSPaddingTop extends CSSSubFieldProperty
{
    public static function parse($value)
    {
        if ($value === 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }
        return PaddingSideValue::init($value);
    }

    public static function getPropertyCode()
    {
        return CSS_PADDING_TOP;
    }

    public static function getPropertyName()
    {
        return 'padding-top';
    }

    public static function default_value()
    {
    }

}

class CSSPaddingRight extends CSSSubFieldProperty
{
    public static function parse($value)
    {
        if ($value === 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }
        $result = PaddingSideValue::init($value);
        return $result;
    }

    public static function getPropertyCode()
    {
        return CSS_PADDING_RIGHT;
    }

    public static function getPropertyName()
    {
        return 'padding-right';
    }

    public static function default_value()
    {
    }

}

class CSSPaddingLeft extends CSSSubFieldProperty
{
    public static function parse($value)
    {
        if ($value === 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }
        return PaddingSideValue::init($value);
    }

    public static function getPropertyCode()
    {
        return CSS_PADDING_LEFT;
    }

    public static function getPropertyName()
    {
        return 'padding-left';
    }

    public static function default_value()
    {
    }

}

class CSSPaddingBottom extends CSSSubFieldProperty
{
    public static function parse($value)
    {
        if ($value === 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }
        return PaddingSideValue::init($value);
    }

    public static function getPropertyCode()
    {
        return CSS_PADDING_BOTTOM;
    }

    public static function getPropertyName()
    {
        return 'padding-bottom';
    }

    public static function default_value()
    {
    }

}

$ph = new CSSPadding;
CSS::register_css_property($ph);
$css_padding_inc_reg1 = new CSSPaddingLeft($ph, 'left');
CSS::register_css_property($css_padding_inc_reg1);
$css_padding_inc_reg2 = new CSSPaddingRight($ph, 'right');
CSS::register_css_property($css_padding_inc_reg2);
$css_padding_inc_reg3 = new CSSPaddingTop($ph, 'top');
CSS::register_css_property($css_padding_inc_reg3);
$css_padding_inc_reg4 = new CSSPaddingBottom($ph, 'bottom');
CSS::register_css_property($css_padding_inc_reg4);
