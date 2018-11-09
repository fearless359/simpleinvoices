<?php
namespace Inc\Claz;

/**
 * InvInfo class with invoice information.
 * @author Rich
 */
class InvInfo {
    public $id;
    public $billed;
    public $paid;
    public $owed;

    /**
     * InvInfo constructor.
     * @param $id
     * @param $billed
     * @param $paid
     * @param $owed
     */
    public function __construct($id, $billed, $paid, $owed) {
        $this->id     = $id;
        $this->billed = $billed;
        $this->paid   = $paid;
        $this->owed   = $owed;
    }
}