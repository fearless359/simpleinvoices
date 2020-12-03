<?php
namespace Inc\Claz;

/**
 * Class NetIncomeItem
 * @package Inc\Claz
 */
class NetIncomeItem
{
    public float $amount;
    public array $cFlags;
    public string $description;
    public float $nonIncAmt;

    /**
     * NetIncomeItem constructor.
     * @param float $amount
     * @param string $description
     * @param string $cFlags
     */
    public function __construct(float $amount, string $description, string $cFlags)
    {
        $this->amount = $amount;
        $this->description = $description;
        $this->nonIncAmt = 0;
        $this->cFlags = [];
        if (!empty($cFlags)) {
            for ($idx = 0; $idx < 10; $idx++) {
                $this->cFlags[$idx] = substr($cFlags, $idx, 1);
            }
        }
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCFlags(): array
    {
        return $this->cFlags;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getNonIncAmt(): float
    {
        return $this->nonIncAmt;
    }

}
