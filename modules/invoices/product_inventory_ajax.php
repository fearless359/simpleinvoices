<?php
use Inc\Claz\Product;
use Inc\Claz\Util;

global $pdoDb;

$id = $_GET['id'] ?? 0;
if (!empty($id)) {
    $row = Product::getOne($id);
    if (empty($row)) {
        echo '';
    }

    $cost = Util::number($row['cost'] ?? 0);
    $output = ['cost' => $cost];

    echo json_encode($output);
    exit();
} else {
    echo "";
}
