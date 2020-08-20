<?php
namespace Inc\Claz;

/**
 * CustInfo class for past due invoices.
 * @author Rich
 */
class CustInfo {
    public string $name;
    public string $fmtdBilled;
    public string $fmtdPaid;
    public string $fmtdOwed;
    public array $invInfo;

    public function __construct(string $name, string $fmtdBilled, string $fmtdPaid, string $fmtdOwed, array $invInfo) {
        $this->name       = $name;
        $this->fmtdBilled = $fmtdBilled;
        $this->fmtdPaid   = $fmtdPaid;
        $this->fmtdOwed   = $fmtdOwed;
        $this->invInfo    = $invInfo;
    }
}
