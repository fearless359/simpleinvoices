<?php

namespace Inc\Claz;

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

}