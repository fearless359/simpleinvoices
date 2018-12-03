<?php
// $Header: /cvsroot/html2ps/output._generic.pdf.class.php,v 1.1 2005/12/13 18:24:45 Konstantin Exp $

class OutputDriverGenericPDF extends OutputDriverGeneric
{
    var $pdf_version;

    public function __construct()
    {
        parent::__construct();
        $this->set_pdf_version("1.3");
    }

    public function content_type()
    {
        return ContentType::pdf();
    }

    public function get_pdf_version()
    {
        return $this->pdf_version;
    }

    public function reset(&$media)
    {
        OutputDriverGeneric::reset($media);
    }

    public function set_pdf_version($version)
    {
        $this->pdf_version = $version;
    }
}
