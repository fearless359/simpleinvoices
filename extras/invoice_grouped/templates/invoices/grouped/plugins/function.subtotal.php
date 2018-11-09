<?php

use Inc\Claz\SiLocal;

/**
 * @param $params
 * @param $smarty
 * @return int|string
 */
function smarty_function_subtotal($params, &$smarty)
{
	$subtotal = 0;
	foreach ($params['cost'] as $key=>$value) {
		if ($value['product']['custom_field1'] == $params['group']) {
			$subtotal = $value['gross_total'] + $subtotal;
		}
	}
	$subtotal = SiLocal::number($subtotal);
	return $subtotal;	
}
