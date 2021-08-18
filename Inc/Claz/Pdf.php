<?php /** @noinspection PhpClassNamingConventionInspection */

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
     * @param string $data html data from template.
     * @param string $pdfname String path to file to save generated PDF to.
     * @param string $destination Setting from Mpdf\Output\Destination.
     * @param bool $landscape true if landscape mode; false if portrait mode.
     * @return string|null If Destination::STRING_RETURN specified, then the
     *      string form of the PDF to attach to an email; otherwise null.
     */
    public static function generate(string $data, string $pdfname, string $destination,
                                    bool $landscape): ?string
    {
        global $config;

        try {
            if (preg_match('/^.*\.pdf$/', $pdfname) !== 1) {
                $pdfname .= '.pdf';
            }

            Log::out("Pdf::generate() - pdfname[$pdfname] destination[$destination] landscape[$landscape] data[$data]");
            $mpdf = new Mpdf([
                'tempDir'           => 'tmp/pdf_tmp',
                'format'            => $config['exportPdfPaperSize'],
                'default_font_size' => $config['exportPdfDefaultFontSize'],
                'margin_left'       => $config['exportPdfLeftMargin'],
                'margin_right'      => $config['exportPdfRightMargin'],
                'margin_top'        => $config['exportPdfTopMargin'],
                'margin_bottom'     => $config['exportPdfBottomMargin'],
                'orientation'       => $landscape ? 'L' : 'P'
            ]);

            Log::out("Pdf::generate() - Before WriteHTML");
            $mpdf->WriteHTML($data);

            Log::out("Pdf::generate() - Before Output");
            $pdfString = $mpdf->Output($pdfname, $destination);

            if ($destination == Destination::STRING_RETURN) {
                return $pdfString;
            }
        } catch (MpdfException $mpdfException) {
            error_log('Pdf::generate(): exception - ' . $mpdfException->getMessage());
        }

        Log::out("Pdf::generate() - returning null");
        return null;
    }

}
