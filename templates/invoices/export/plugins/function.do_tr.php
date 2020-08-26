<?php

/**
 * Generate statement that terminate previous <tr> tag and generate the next one.
 * @param array $params
 * @param object $smarty
 * @return string|void
 * @noinspection PhpUnusedParameterInspection
 */
function smartyFunctionsDoTr(array $params, object &$smarty)
{
	if ($params['number'] == 2 ) {
		return "</tr><tr class='{$params['class']}'>";
	}
	
    if ($params['number'] == 4 ) {
        return "</tr><tr class='{$params['class']}'>";
    }

}
