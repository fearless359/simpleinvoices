<?php

use Inc\Claz\Util;

/**
 * @param $params
 * @param $smarty
 * @return int|string
 */
function smarty_function_total($params, &$smarty)
{
	$subtotal = 0;
	foreach ($params['cost'] as $key=>$value) {
		if ($value['product']['custom_field1'] == $params['group']) {
			$subtotal = $value['total'] + $subtotal;
		}
	}
	$subtotal = Util::number($subtotal);
	return $subtotal;	
}
