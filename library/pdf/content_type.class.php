<?php

class ContentType
{
    var $default_extension;
    var $mime_type;

    public function __construct($extension, $mime)
    {
        $this->default_extension = $extension;
        $this->mime_type = $mime;
    }

    public static function png()
    {
        return new ContentType('png', 'image/png');
    }

    public static function gz()
    {
        return new ContentType('gz', 'application/gzip');
    }

    public static function pdf()
    {
        return new ContentType('pdf', 'application/pdf');
    }

    public static function ps()
    {
        return new ContentType('ps', 'application/postscript');
    }
}
