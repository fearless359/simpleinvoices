<?php

use Inc\Claz\Util;

/**
 * Function: print_if_not_empty
 *
 * Used in the print preview to determine if a row/field gets printed.
 * Basically if the field is empty don't print it. Note that the empty()
 * function differs from the isset() function as a non-null but blank, 0,
 * etc. type fields are considered empty.
 *
 * @param array $params associative array with the following key/value pairs:
 *   label   - The name of the field, i.e. Custom Field 1, Email, etc.
 *             This can be a string or an array of strings. Elements of an
 *             array of strings will be concatenated to make the label.
 *             Don't pass or pass empty string to suppress.
 *   field   - The actual value to be printed, i.e. total, paid, owing, etc.
 *             This can be a string or an array of strings. Elements of an
 *             array of strings will be concatenated to make the field.
 *   class1  - the css class of the <th> heading
 *   class2  - the css class of the second <td> detail
 *   colspan - the colspan of the last td
 *   printIfEmpty - Set true if empty lines should be printed if no field present.
 */
function smarty_function_print_if_not_empty(array $params): void
{
    $printIfEmpty = !empty($params['printIfEmpty']) && $params['printIfEmpty'] == true;
    $class1 = empty($params['class1']) ? '' : Util::htmlSafe($params['class1']);
    $class2 = empty($params['class2']) ? '' : Util::htmlSafe($params['class2']);

    // If the data field is empty, do not print a line unless option to print set.
    if (empty($params["field"])) {
        if ($printIfEmpty) {
            $str = "<tr>" .
                "<th class='$class1'>&nbsp;</th>" .
                "<td class='$class2'>&nbsp;</td>" .
                "</tr>";
            echo $str;
        }
    } else {
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
        $str = "<tr>" .
               "<th class='$class1'>";
        if (!empty($label)) {
            $str .= $label . ':&nbsp;';
        }
        $str .= "</th>";

        $str .= "<td class='$class2' colspan='" . Util::htmlSafe($params['colspan']) . "'>";
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
