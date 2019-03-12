<?php

namespace Inc\Claz;

use DataFilterUTF8;
use Media;
use PipelineFactory;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;

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
     * Generates PDF output to specified destination.
     * @param string $html_to_pdf html path to source html file.
     * @param string $pdfname String path to file to save generated PDF to.
     * @param Destination $destination Setting from \
     * @return string/null If Destination::STRING_RETURN specified, then the
     *      string form of the PDF to attach to an email; otherwise null.
     */
    public static function generate($html_to_pdf, $pdfname, $destination)
    {
        global $config;

        try {
            if (preg_match('/^.*\.pdf$/', $pdfname) !== 1) {
                $pdfname .= '.pdf';
            }

            $mpdf = new Mpdf([
                'tempDir'           => 'tmp/pdf_tmp',
                'format'            => $config->export->pdf->papersize,
                'default_font_size' => $config->export->pdf->defaultfontsize,
                'margin_left'       => $config->export->pdf->leftmargin,
                'margin_right'      => $config->export->pdf->rightmargin,
                'margin_top'        => $config->export->pdf->topmargin,
                'margin_bottom'     => $config->export->pdf->bottommargin
            ]);
            $mpdf->WriteHTML($html_to_pdf);

            $result = $mpdf->Output($pdfname, $destination);
            if ($destination == Destination::STRING_RETURN) {
                return $result;
            }
        } catch (MpdfException $mpdfException) {
            error_log('Pdf::generate(): exception - ' . $mpdfException->getMessage());
        }
        return null;
    }

    /**
     * @param $html_to_pdf
     * @param $pdfname
     * @param $download
     */
//    private static function convertToPdf($html_to_pdf, $pdfname, $download) {
//        global $config;
//
//        $destination = $download ? "DestinationDownload" : "DestinationFile";
//        $pipeline = PipelineFactory::create_default_pipeline("", ""); // Attempt to auto-detect encoding
//        $pipeline->fetchers[] = new MyFetcherLocalFile($html_to_pdf); // Override HTML source
//        $baseurl = "";
//        $media = Media::predefined($config->export->pdf->papersize);
//        $media->set_landscape(false);
//
//        $margins = array(
//            'left'   => $config->export->pdf->leftmargin,
//            'right'  => $config->export->pdf->rightmargin,
//            'top'    => $config->export->pdf->topmargin,
//            'bottom' => $config->export->pdf->bottommargin);
//
//        global $g_config;
//        $g_config = array(
//            'cssmedia'                => 'screen',
//            'renderimages'            => true,
//            'renderlinks'             => true,
//            'renderfields'            => true,
//            'renderforms'             => false,
//            'mode'                    => 'html',
//            'encoding'                => '',
//            'debugbox'                => false,
//            'pdfversion'              => '1.4',
//            'process_mode'            => 'single',
//            'pixels'                  => $config->export->pdf->screensize,
//            'media'                   => $config->export->pdf->papersize,
//            'margins'                 => $margins,
//            'transparency_workaround' => 1,
//            'imagequality_workaround' => 1,
//            'draw_page_border'        => false);
//
//        $media->set_margins($g_config['margins']);
//        $media->set_pixels($config->export->pdf->screensize);
//
//        global $g_px_scale;
//        $g_px_scale = mm2pt($media->width() - $media->margins['left'] - $media->margins['right']) / $media->pixels;
//
//        global $g_pt_scale;
//        $g_pt_scale = $g_px_scale * (72 / 96);
//
//        $pipeline->configure($g_config);
//        $pipeline->data_filters[] = new DataFilterUTF8("");
//        $pipeline->destination = new $destination($pdfname);
//        $pipeline->process($baseurl, $media);
//    }

}