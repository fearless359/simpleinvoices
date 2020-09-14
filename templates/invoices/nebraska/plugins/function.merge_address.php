<?php

use Inc\Claz\Util;

/**
 * Function: merge_address
 *
 * Merges the city, state, and zip info onto one live and takes into account the commas
 *
 * @param array $params associative array with the following key/value content:
 *  field1  - normally city
 *  field2  - normally state
 *  field3  - normally zip
 *  street1 - street 1 added print the word "Address:" on the first line of the invoice
 *  street2 - street 2 added print the word "Address:" on the first line of the invoice
 *  class1  - the css class for the first td
 *  class2  - the css class for the second td
 *  colspan - the td colspan of the last td
 * @param object $smarty - unused
 *
 * @noinspection PhpUnusedParameterInspection
 */
function smarty_function_merge_address($params, &$smarty) {
		global $LANG;
		$skipSection = false;
		$ma = '';
		// If any among city, state or zip is present with no street at all
        if (($params['field1'] != null OR $params['field2'] != null OR $params['field3'] != null) AND $params['street1'] ==null AND $params['street2'] ==null) {
                $ma .=  "<tr>" .
				            "<td class='". Util::htmlSafe($params['class1'])."'>{$LANG['address']}:</td>" .
				            "<td class='". Util::htmlSafe($params['class2'])."' colspan='".Util::htmlSafe($params['colspan'])."'>";
		$skipSection = true;
        }
		// If any among city, state or zip is present with atleast one street value
        if (($params['field1'] != null OR $params['field2'] != null OR $params['field3'] != null) AND ! $skipSection) {
                $ma .=  "<tr>" .
				            "<td class='" . Util::htmlSafe($params['class1']) . "'></td>" .
				            "<td class='" . Util::htmlSafe($params['class2']) . "' colspan='" . Util::htmlSafe($params['colspan']) . "'>";
        }
        if ($params['field1'] != null) {
                $ma .=  Util::htmlSafe($params['field1']);
        }

        if ($params['field1'] != null AND $params['field2'] != null  ) {
                $ma .=  ", ";
        }

        if ($params['field2'] != null) {
                $ma .=  Util::htmlSafe($params['field2']);
        }

        if (($params['field1'] != null OR $params['field2'] != null) AND $params['field3'] != null) {
                $ma .=  ", ";
        }

        if ($params['field3'] != null) {
                $ma .=  Util::htmlSafe($params['field3']);
        }
		
	$ma .=     "</td>" .
		   "</tr>";
	echo $ma;
}
