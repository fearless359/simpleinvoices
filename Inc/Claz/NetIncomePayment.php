<?php
namespace Inc\Claz;

/**
 * Class NetIncomePayment
 * @package Inc\Claz
 */
class NetIncomePayment
{
    public float $amount;
    public array $cflags;
    public string $date;

    /**
     * NetIncomePayment constructor.
     * @param float $amount
     * @param string $date
     * @param string $cflags
     */
    public function __construct(float $amount, string $date, string $cflags = "")
    {
        $this->amount = $amount;
        $this->date = $date;
        $this->cflags = [];
        if (!empty($cflags)) {
            for ($idx = 0; $idx < 10; $idx++) {
                $this->cflags[$idx] = substr($cflags, $idx, 1);
            }
        }
    }
}