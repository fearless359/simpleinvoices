<?php /** @noinspection PhpClassNamingConventionInspection */

namespace Inc\Claz;

use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;

use Zend_Log;

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
     * @param string $htmlToPdf html path to source html file.
     * @param string $pdfname String path to file to save generated PDF to.
     * @param string $destination Setting from Mpdf\Output\Destination.
     * @return string|null If Destination::STRING_RETURN specified, then the
     *      string form of the PDF to attach to an email; otherwise null.
     */
    public static function generate(string $htmlToPdf, string $pdfname, string $destination)
    {
        global $config;

        try {
            if (preg_match('/^.*\.pdf$/', $pdfname) !== 1) {
                $pdfname .= '.pdf';
            }

            Log::out("Pdf::generate() - pdfname[{$pdfname}] destination[{$destination}]", Zend_Log::DEBUG);
            $mpdf = new Mpdf([
                'tempDir'           => 'tmp/pdf_tmp',
                'format'            => $config->export->pdf->papersize,
                'default_font_size' => $config->export->pdf->defaultfontsize,
                'margin_left'       => $config->export->pdf->leftmargin,
                'margin_right'      => $config->export->pdf->rightmargin,
                'margin_top'        => $config->export->pdf->topmargin,
                'margin_bottom'     => $config->export->pdf->bottommargin
            ]);

            Log::out("Pdf::generate() - Before WriteHTML", Zend_Log::DEBUG);
            $mpdf->WriteHTML($htmlToPdf);

            Log::out("Pdf::generate() - Before Output", Zend_Log::DEBUG);
            $result = $mpdf->Output($pdfname, $destination);

            if ($destination == Destination::STRING_RETURN) {
                Log::out("Pdf::generate() - returning Output result", Zend_Log::DEBUG);
                return $result;
            }
        } catch (MpdfException $mpdfException) {
            error_log('Pdf::generate(): exception - ' . $mpdfException->getMessage());
        }

        Log::out("Pdf::generate() - returning null", Zend_Log::DEBUG);
        return null;
    }

}