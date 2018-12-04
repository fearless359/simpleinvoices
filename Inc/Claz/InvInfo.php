<?php
namespace Inc\Claz;

/**
 * InvInfo class with invoice information.
 * @author Rich
 */
class InvInfo {
    public $id;
    public $index_id;
    public $billed;
    public $paid;
    public $owed;

    /**
     * InvInfo constructor.
     * @param $id
     * @param $index_id
     * @param $billed
     * @param $paid
     * @param $owed
     */
    public function __construct($id, $index_id, $billed, $paid, $owed) {
        $this->id       = $id;
        $this->index_id = $index_id;
        $this->billed   = $billed;
        $this->paid     = $paid;
        $this->owed     = $owed;
    }
}