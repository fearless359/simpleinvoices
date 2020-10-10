<?php
namespace Inc\Claz;

/**
 * CustInfo class for past due invoices.
 * @author Rich
 */
class CustInfo {
    public string $name;
    public string $billed;
    public string $paid;
    public string $owed;
    public array $invInfo;

    public function __construct(string $name, string $billed, string $paid, string $owed, array $invInfo) {
        $this->name    = $name;
        $this->billed  = $billed;
        $this->paid    = $paid;
        $this->owed    = $owed;
        $this->invInfo = $invInfo;
    }
}
