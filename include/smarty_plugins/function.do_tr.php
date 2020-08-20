<?php
function smartyFunctionsDoTr($params) {
    $newTr = null;
    if ($params['number'] == 2) {
        $newTr = "</tr><tr class='$params[class]'>";
    } elseif ($params['number'] == 4) {
        $newTr = "</tr><tr class='$params[class]'>";
    }
    if (isset($newTr)) {
        return $newTr;
    }
}
