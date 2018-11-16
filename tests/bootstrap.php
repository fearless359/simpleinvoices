<?php
/**
 * @name bootstrap.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181115
 */
$lcl_path = get_include_path() .
    PATH_SEPARATOR . "./library/" .
    PATH_SEPARATOR . "./library/pdf" .
    PATH_SEPARATOR . "./library/pdf/fpdf" .
    PATH_SEPARATOR . "./include/" .
    PATH_SEPARATOR . "./vendor/";
if (set_include_path($lcl_path) === false) {
    error_log("Error reported by set_include_path() for path: {$lcl_path}");
}

require_once 'Zend/Loader/Autoloader.php';

global $autoloader;

/* *************************************************************
 * Zend framework init - beg
 * *************************************************************/
$autoloader = \Zend_Loader_Autoloader::getInstance();
$autoloader->setFallbackAutoloader(true);
