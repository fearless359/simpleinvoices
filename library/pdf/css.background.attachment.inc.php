<?php

define('BACKGROUND_ATTACHMENT_SCROLL', 1);
define('BACKGROUND_ATTACHMENT_FIXED', 2);

class CSSBackgroundAttachment extends CSSSubFieldProperty
{
    public static function getPropertyCode()
    {
        return CSS_BACKGROUND_ATTACHMENT;
    }

    public static function getPropertyName()
    {
        return 'background-attachment';
    }

    public static function default_value()
    {
        return BACKGROUND_ATTACHMENT_SCROLL;
    }

    public static function &parse($value_string)
    {
        if ($value_string === 'inherit') {
            $value = CSS_PROPERTY_INHERIT;
        } else {
            if (preg_match('/\bscroll\b/', $value_string)) {
                $value = BACKGROUND_ATTACHMENT_SCROLL;
            } elseif (preg_match('/\bfixed\b/', $value_string)) {
                $value = BACKGROUND_ATTACHMENT_FIXED;
            } else {
                $value = BACKGROUND_ATTACHMENT_SCROLL;
            }
        }

        return $value;
    }
}
