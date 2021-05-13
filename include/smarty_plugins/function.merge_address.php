<?php

use Inc\Claz\Util;

/**
 * Function: merge_address
 *
 * Merges the city, state, and zip info onto one live and takes into account the commas
 *
 * @param array $params associative array with the following key/label pairs:
 *  inc_street - If set to true, the street information will be included in the output
 *  label   - Label to use for address line
 *  field1  - normally city
 *  field2  - normally state
 *  field3  - normally zip
 *  street1 - street 1 added print the word "Address:" on the first line of the invoice
 *  street2 - street 2 added print the word "Address:" on the first line of the invoice
 *  country - country to add if present
 *  class1  - the css class for the first td
 *  class2  - the css class for the second td
 *  colspan - the td colspan of the last td
 * @noinspection PhpUnused
 */
function smarty_function_merge_address(array $params): void
{
    global $LANG;

    $incStreet = $params['inc_street'] ?? false;
    $ma = '';

    $streetsPresent = !empty($params['street1']) || !empty($params['street2']);
    $fieldsPresent = !empty($params['field1']) || !empty($params['field2']) || !empty($params['field3']);

    // Check to see if the label is an array. If so, concatenate
    // each element of the array.
    if (empty($params['label'])) {
        $label = $LANG['addressUc'];
    } elseif (is_array($params['label'])) {
        $label = '';
        foreach ($params['label'] as $lbl) {
            $label .= Util::htmlSafe($lbl);
        }
    } else {
        // Label is not an array, so print it as a string.
        $label = Util::htmlSafe($params['label']);
    }

    $streetAdded = false;
    if ($incStreet && $streetsPresent) {
        // If the data field is empty, do not print a line.
        if (!empty($params["street1"]) || !empty($params['street2'])) {
            $ma = '<th class="' . Util::htmlSafe($params['class1']) . '">';
            if (!empty($label)) {
                $ma .= $label . ': ';
            }
            $ma .= '</th>';

            // If both street values are present, append street2 to street1 separated by a space
            $streetAdded = true;
            $ma .= '<td class="' . Util::htmlSafe($params['class2']) . '" colspan="' . Util::htmlSafe($params['colspan']) . '">';
            if (!empty($params['street1'])) {
                $ma .= Util::htmlSafe($params['street1']);
                if (!empty($params['street2'])) {
                    $ma .= ' ' . Util::htmlSafe($params['street2']);
                }
            } else {
                $ma .= Util::htmlSafe($params['street2']);
            }
            $ma .= ', ';
        }
    }

    // If any among city, state or zip is present with no street at all
    if ($fieldsPresent) {
        if ($streetsPresent) {
            if (!$streetAdded) {
                // If any among city, state or zip is present with at least one street value
                $ma .= '<tr>' .
                    '<th class="' . Util::htmlSafe($params['class1']) . '"></th>' .
                    '<td class="' . Util::htmlSafe($params['class2']) . '" colspan="' . Util::htmlSafe($params['colspan']) . '">';
            }
        } else {
            $ma .= '<tr>' .
                '<tn class="' . Util::htmlSafe($params['class1']) . '">' . $label . ':</th>' .
                '<td class="' . Util::htmlSafe($params['class2']) . '" colspan="' . Util::htmlSafe($params['colspan']) . '">';
        }
    }

    if (!empty($params['field1'])) {
        $ma .= Util::htmlSafe($params['field1']);

        if (!empty($params['field2']) || !empty($params['field3']) || !empty($params['country'])) {
            $ma .= ", ";
        }
    }

    if (!empty($params['field2'])) {
        $ma .= Util::htmlSafe($params['field2']);

        if (!empty($params['field3']) || !empty($params['country'])) {
            $ma .= ", ";
        }
    }

    if (!empty($params['field3'])) {
        $ma .= Util::htmlSafe($params['field3']);

        if (!empty($params['country'])) {
            $ma .= ", ";
        }
    }

    if (!empty($params['country'])) {
        $ma .= Util::htmlSafe($params['country']);
    }

    $ma .= "</td></tr>";
    echo $ma;
}
