<?php
namespace Inc\Claz;

/**
 * CustInfo class for past due invoices.
 * @author Rich
 */
class CustInfo {
    public $name;
    public $billed;
    public $paid;
    public $owed;
    public $inv_info;

    /**
     * CustInfo constructor.
     * @param $name
     * @param $billed
     * @param $paid
     * @param $owed
     * @param $inv_info
     */
    public function __construct($name, $billed, $paid, $owed, $inv_info) {
        $this->name     = $name;
        $this->billed   = $billed;
        $this->paid     = $paid;
        $this->owed     = $owed;
        $this->inv_info = $inv_info;
    }
}
