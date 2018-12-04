<?php

namespace Inc\Claz;

/**
 * @name Pdf.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181123
 *
 * Class Pdf
 * @package Inc\Claz
 */
class Pdf
{

    /**
     * Runs the HTML->PDF conversion with default settings
     * Warning: if you have any files (like CSS stylesheets and/or images referenced by this file,
     * use absolute links (like http://my.host/image.gif).
     * @param string $html_to_pdf html path to source html file.
     * @param string $pdfname String path to file to save generated PDF to.
     * @param boolean $download <b>true</b> sets <i>DestinationDownload</i> for the output destination.
     *        <b>false</b> sets <i>DestinationFile</i> for the output destination.
     */
    public static function pdfThis($html_to_pdf, $pdfname, $download) {
        require_once ('./library/pdf/config.inc.php');
        require_once ('./library/pdf/pipeline.factory.class.php');
        require_once ('./library/pdf/pipeline.class.php');

        parse_config_file('./library/pdf/html2ps.config');

        require_once ("include/init.php"); // for getInvoice() and getPreference()

        self::convert_to_pdf($html_to_pdf, $pdfname, $download);
    }

    /**
     * @param $html_to_pdf
     * @param $pdfname
     * @param $download
     */
    private static function convert_to_pdf($html_to_pdf, $pdfname, $download) {
        global $config;

        $destination = $download ? "DestinationDownload" : "DestinationFile";
        $pipeline = \PipelineFactory::create_default_pipeline("", ""); // Attempt to auto-detect encoding
        $pipeline->fetchers[] = new MyFetcherLocalFile($html_to_pdf); // Override HTML source
        $baseurl = "";
        $media = \Media::predefined($config->export->pdf->papersize);
        $media->set_landscape(false);

        $margins = array(
            'left'   => $config->export->pdf->leftmargin,
            'right'  => $config->export->pdf->rightmargin,
            'top'    => $config->export->pdf->topmargin,
            'bottom' => $config->export->pdf->bottommargin);

        global $g_config;
        $g_config = array(
            'cssmedia'                => 'screen',
            'renderimages'            => true,
            'renderlinks'             => true,
            'renderfields'            => true,
            'renderforms'             => false,
            'mode'                    => 'html',
            'encoding'                => '',
            'debugbox'                => false,
            'pdfversion'              => '1.4',
            'process_mode'            => 'single',
            'pixels'                  => $config->export->pdf->screensize,
            'media'                   => $config->export->pdf->papersize,
            'margins'                 => $margins,
            'transparency_workaround' => 1,
            'imagequality_workaround' => 1,
            'draw_page_border'        => false);

        $media->set_margins($g_config['margins']);
        $media->set_pixels($config->export->pdf->screensize);

        global $g_px_scale;
        $g_px_scale = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) / $media->pixels;

        global $g_pt_scale;
        $g_pt_scale = $g_px_scale * (72 / 96);

        $pipeline->configure($g_config);
        $pipeline->data_filters[] = new \DataFilterUTF8("");
        $pipeline->destination = new $destination($pdfname);
        $pipeline->process($baseurl, $media);
    }

}