<?php

use Inc\Claz\Util;

/**
 * Function: print_if_not_null
 *
 * Used in the print preview to determine if a row/field gets printed.
 * Basically if the field is null dont print it. Note that the isset()
 * function differs from the empty() function as a non-null but blank, 0,
 * etc. type fields are consider empty but is not considered null..
 *
 * @param array $params associative array with the following key/value pairs:
 *   label   - The name of the field, ie. Custom Field 1, Email, etc.
 *             This can be a string or an array of strings. Elements of an
 *             array of strings will be concatenated to make the label.
 *             Don't pass or pass empty string to suppress.
 *   field   - The actual value to be printed, ie. total, paid, owing, etc.
 *             This can be a string or an array of strings. Elements of an
 *             array of strings will be concatenated to make the field.
 *   class1  - the css class of the first td
 *   class2  - the css class of the second td
 *   colspan - the colspan of the last td
 */
function smarty_function_print_if_not_null(array $params): void
{
    // If the data field is null, do not print a line.
    if (isset($params['field'])) {
        // Check to see if the label is an array. If so, concatenate
        // each element of the array.
        if (is_array($params['label'])) {
            $label = '';
            foreach ($params['label'] as $lbl) {
                $label .= Util::htmlSafe($lbl);
            }
        } else {
            // Label is not an array, so print it as a string.
            $label = Util::htmlSafe($params['label']);
        }
        $str = '<tr>' .
            '<th class="' . Util::htmlSafe($params['class1']) . '">';
        if (!empty($label)) {
            $str .= $label . ': ';
        }
        $str .= '</th>';

        $str .= '<td class="' . Util::htmlSafe($params['class2']) . '" colspan="' . Util::htmlSafe($params['colspan']) . '">';
        if (is_array($params['field'])) {
            $field = '';
            foreach ($params['field'] as $fld) {
                $field .= Util::htmlSafe($fld);
            }
        } else {
            $field = Util::htmlSafe($params['field']);
        }
        $str .= $field .'</td></tr>';
        echo $str;
    }
}
