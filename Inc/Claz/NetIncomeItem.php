<?php
namespace Inc\Claz;

/**
 * Class NetIncomeItem
 * @package Inc\Claz
 */
class NetIncomeItem
{
    public $amount;
    public $description;
    public $cflags;
    public $non_inc_amt;

    /**
     * NetIncomeItem constructor.
     * @param $amount
     * @param $description
     * @param $cflags
     */
    public function __construct($amount, $description, $cflags)
    {
        $this->amount = $amount;
        $this->description = $description;
        $this->non_inc_amt = 0;
        $this->cflags = array();
        if (isset($cflags)) {
            for ($i = 0; $i < 10; $i++) {
                $this->cflags[$i] = substr($cflags, $i, 1);
            }
        }
    }
}
