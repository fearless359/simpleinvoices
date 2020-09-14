<?php

use Inc\Claz\Util;

/**
 * @param $params
 * @param $smarty
 * @return float
 */
function smarty_function_markup($params, &$smarty)
{
	$subtotal = 0;
	foreach ($params['cost'] as $key=>$value) {
		if ($value['product']['custom_field1'] == $params['group']) {
			$subtotal = $value['tax_amount'] + $subtotal;
		}
	}
	$subtotal = Util::number($subtotal);
	return $subtotal;	
}

