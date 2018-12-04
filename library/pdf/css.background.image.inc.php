<?php
// $Header: /cvsroot/html2ps/css.background.image.inc.php,v 1.16 2006/07/09 09:07:44 Konstantin Exp $

class CSSBackgroundImage extends CSSSubFieldProperty
{
    public static function getPropertyCode()
    {
        return CSS_BACKGROUND_IMAGE;
    }

    public static function getPropertyName()
    {
        return 'background-image';
    }

    public static function default_value()
    {
        return new BackgroundImage(null, null);
    }

    public static function parse($value, &$pipeline)
    {
        global $g_config;
        if (!$g_config['renderimages']) {
            return self::default_value();
        }

        if ($value === 'inherit') {
            return CSS_PROPERTY_INHERIT;
        }

        // 'url' value
        if (preg_match("/url\((.*[^\\\\]?)\)/is", $value, $matches)) {
            $url = $matches[1];

            $full_url = $pipeline->guess_url(css_remove_value_quotes($url));
            return new BackgroundImage($full_url, Image::get($full_url, $pipeline));
        }

        // 'none' and unrecognized values
        return self::default_value();
    }
}
