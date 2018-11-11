<?php
/* Class: wrapper class for owing_ttl           */
 class TotalOwing
 {
    public static $Owing_total;
     public function setAmt($invar) {
        TotalOwing::$Owing_total = $invar;
    }
    public function addAmt($invar) {
        TotalOwing::$Owing_total += $invar;
    }
}
