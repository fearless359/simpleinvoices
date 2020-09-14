<?php
use Inc\Claz\Util;

/**
 * Function: merge_address
 *
 * Merges the city, state, and zip info onto one live and takes into account the commas
 *
 * @param array $params associative array with the following key/label pairs:
 *  field1  - normally city
 *  field2  - normally state
 *  field3  - normally zip
 *  street1 - street 1 added print the word "Address:" on the first line of the invoice
 *  street2 - street 2 added print the word "Address:" on the first line of the invoice
 *  class1  - the css class for the first td
 *  class2  - the css class for the second td
 *  colspan - the td colspan of the last td
 */
function smarty_function_merge_address(array $params) {
    global $LANG;
    $skipSection = false;
    $ma = '';
    // If any among city, state or zip is present with no street at all
    if (empty($params['street1']) && empty($params['street2']) &&
            (!empty($params['field1']) || !empty($params['field2']) || !empty($params['field3']))) {
        $ma .= '<tr>' .
                   '<td class="' . Util::htmlSafe($params['class1']) . '" >' . $LANG['address_uc'] . ':</td>' .
                   '<td class="' . Util::htmlSafe($params['class2']) . '" colspan="' . Util::htmlSafe($params['colspan']) . '" >';
        $skipSection = true;
    }

    // If any among city, state or zip is present with atleast one street value
    if (!$skipSection && (!empty($params['field1']) || !empty($params['field2']) || !empty($params['field3']))) {
        $ma .= '<tr>' .
                   '<td class="' . Util::htmlSafe($params['class1']) . '"></td>' .
                   '<td class="' . Util::htmlSafe($params['class2']) . '" colspan="' . Util::htmlSafe($params['colspan']) . '">';
    }

    if (!empty($params['field1'])) {
        $ma .= Util::htmlSafe($params['field1']);
    }

    if (!empty($params['field1']) && !empty($params['field2'])) {
        $ma .= ", ";
    }

    if (!empty($params['field2'])) {
        $ma .= Util::htmlSafe($params['field2']);
    }

    if (!empty($params['field3']) && (!empty($params['field1']) || !empty($params['field2']))) {
        $ma .= ", ";
    }

    if (!empty($params['field3'])) {
        $ma .= Util::htmlSafe($params['field3']);
    }

    $ma .= "</td></tr>";
    echo $ma;
}
