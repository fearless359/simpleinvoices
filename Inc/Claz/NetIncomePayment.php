<?php
namespace Inc\Claz;

/**
 * Class NetIncomePayment
 * @package Inc\Claz
 */
class NetIncomePayment
{
    public $amount;
    public $cflags;
    public $date;

    /**
     * NetIncomePayment constructor.
     * @param $amount
     * @param $date
     * @param null $cflags
     */
    public function __construct($amount, $date, $cflags = null)
    {
        $this->amount = $amount;
        $this->date = $date;
        $this->cflags = array();
        if (isset($cflags)) {
            for ($i = 0; $i < 10; $i++) {
                $this->cflags[$i] = substr($cflags, $i, 1);
            }
        }
    }
}