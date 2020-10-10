<?php

namespace Inc\Claz;

/**
 * InvInfo class with invoice information.
 * @author Rich
 */
class InvInfo
{
    public int $id;
    public int $indexId;
    public string $billed;
    public string $paid;
    public string $owed;

    /**
     * InvInfo constructor.
     * @param int $id
     * @param int $indexId
     * @param string $billed
     * @param string $paid
     * @param string $owed
     */
    public function __construct(int $id, int $indexId, string $billed, string $paid, string $owed)
    {
        $this->id      = $id;
        $this->indexId = $indexId;
        $this->billed  = $billed;
        $this->paid    = $paid;
        $this->owed    = $owed;
    }
}
