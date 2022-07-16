<?php
/**
 * @deprecated This file has been replaced by generating the holiday logo in the logo
 *             field exported for the template.
 *
 * Get the logo file for current holiday (if one)
 * @param array $params array with index of "logo" and value of default logo file path.
 * @return string logo file path to use
 */
function smarty_function_holiday_logo(array $params): string
{
    // Do nothing except return what was sent to this function.
    error_log("**** Call to deprecated function. Source: include/smarty_plugins/function.holiday_logo.php. This function no longer needed.").
    $logo = $params['logo'];
    return $logo;
}
