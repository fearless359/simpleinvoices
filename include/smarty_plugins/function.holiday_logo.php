<?php
/**
 * Get the logo file for current holiday (if one)
 * @param array $params array with index of "logo" and value of default logo file path.
 * @return string logo file path to use
 */
function smarty_function_holiday_logo(array $params): string
{
    // @formatter:off
    $holidays = [
        "_newyears."     => "1",
        "_valentines."   => "2",
        "_easter."       => "4",
        "_independence." => "7",
        "_thanksgiving." => "11",
        "_christmas."    => "12"
    ];
    // @formatter:on

    $logo = $params['logo'];
    $parts = explode('.', $logo);
    if (count($parts) == 2) {
        $relPath = substr($parts[0], strlen($_SERVER['FULL_URL']) + 1);
        $now = new DateTime();
        $currMonth = $now->format('m');
        foreach($holidays as $holiday => $month) {
            if ($currMonth == $month) {
                $tmpLogo = $relPath . $holiday . $parts[1];
                if (file_exists($tmpLogo)) {
                    if (empty($_SERVER['FULL_URL'])) {
                        $logo = $tmpLogo;
                    } else {
                        $logo = $_SERVER['FULL_URL'] . $tmpLogo;
                    }
                }
                break;
            }
        }
    }
    return $logo;
}
