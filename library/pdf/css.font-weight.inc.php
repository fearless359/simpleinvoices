<?php

class CSSFontWeight extends CSSSubFieldProperty
{
    public static function default_value()
    {
        return WEIGHT_NORMAL;
    }

    public static function parse($value)
    {
        switch (trim(strtolower($value))) {
            case 'inherit':
                return CSS_PROPERTY_INHERIT;
            case 'bold':
            case '700':
            case '800':
            case '900':
            case 'bolder':
                return WEIGHT_BOLD;
            case 'lighter':
            case 'normal':
            case '100':
            case '200':
            case '300':
            case '400':
            case '500':
            case '600':
            default:
                return WEIGHT_NORMAL;
        }
    }

    public static function getPropertyCode()
    {
        return CSS_FONT_WEIGHT;
    }

    public static function getPropertyName()
    {
        return 'font-weight';
    }
}
