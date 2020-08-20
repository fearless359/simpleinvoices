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
    public string $fmtdBilled;
    public string $fmtdPaid;
    public string $fmtdOwed;

    /**
     * InvInfo constructor.
     * @param int $id
     * @param int $indexId
     * @param string $fmtdBilled
     * @param string $fmtdPaid
     * @param string $fmtdOwed
     */
    public function __construct(int $id, int $indexId, string $fmtdBilled, string $fmtdPaid, string $fmtdOwed)
    {
        $this->id = $id;
        $this->indexId = $indexId;
        $this->fmtdBilled = $fmtdBilled;
        $this->fmtdPaid = $fmtdPaid;
        $this->fmtdOwed = $fmtdOwed;
    }
}