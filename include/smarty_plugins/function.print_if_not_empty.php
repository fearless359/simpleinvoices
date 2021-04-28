<?php

use Inc\Claz\Util;

/**
 * Function: print_if_not_empty
 *
 * Used in the print preview to determine if a row/field gets printed.
 * Basically if the field is empty dont print it. Note that the empty()
 * function differs from the isset() function as a non-null but blank, 0,
 * etc. type fields are consider empty.
 *
 * @param array $params associative array with the following key/value pairs:
 *   label   - The name of the field, ie. Custom Field 1, Email, etc..
 *   field   - The actual value from the db ie, test@test.com for email etc...
 *   class1  - the css class of the first td
 *   class2  - the css class of the second td
 *   colspan - the colspan of the last td
 * @noinspection PhpUnused
 */
function smarty_function_print_if_not_empty(array $params): void
{
    if (!empty($params["field"])) {
        echo '<tr>' .
            '<td class="' . Util::htmlSafe($params['class1']) . '">' . Util::htmlSafe($params['label']) . ': </td>' .
            '<td class="' . Util::htmlSafe($params['class2']) . '" colspan="' . Util::htmlSafe($params['colspan']) . '">' . Util::htmlSafe($params['field']) . '</td>' .
            '</tr>';
    }
}
