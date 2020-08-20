<?php

function smartyFunctionsDoTr($params, &$smarty)
{
	if ($params['number'] == 2 ) {
		$newTr = "</tr><tr class='$params[class]'>";
		return $newTr;
	}
	
        if ($params['number'] == 4 ) {
                $newTr = "</tr><tr class='$params[class]'>";
                return $newTr;
        }

}


?>
