<?php

/**
 * Class Encryption
 */
class Encryption
{
    private const SCRAMBLE1 = '! "#%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOPQRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~';
    private const SCRAMBLE2 = 'f^jAE]okI\OzU[2&q1{3`h5w_794p@6s8?BgP>dFV=m D<TcS%Ze|r:lGK/uCy.Jx)HiQ!"#\'~(;Lt-R}Ma,NvW+Ynb*0X';

    private float $adj;    // 1st adjustment value (optional)
    private int $mod;

    /**
     * class constructor
     */
    public function __construct()
    {
        $this->adj = 1.75; // this value is added to the rolling fudge factors
        $this->mod = 3; // if divisible by this the adjustment is made negative
    }

    /**
     * Decrypt previously encrypted key.
     * @param string $key Encryption key.
     * @param string $source Value to be encrypted.
     * @return string|null Encrypted value.
     * @throws Exception if an error occurs.
     */
    public function decrypt(string $key, string $source): ?string
    {
        if (empty($source)) {
            throw new Exception('No value has been supplied for decryption');
        }

        $target = null;
        $factor2 = 0;

        $key .= "" . strlen($source);
        $key = md5($key);

        // Convert $key into a sequence of numbers. Note that if an error
        // is thrown, it will be pass on.
        $fudgeFactor = self::_convertKey($key);

        for ($idx = 0; $idx < strlen($source); $idx++) {
            // extract a character from $source
            $char2 = substr($source, $idx, 1);
            // identify its position in $scramble2
            $num2 = strpos(self::SCRAMBLE2, $char2);
            if ($num2 === false) {
                throw new Exception("Source string contains an invalid character ($char2)");
            }

            // get an adjustment value using $fudgeFactor
            // @formatter:off
            $adj     = $this->_applyFudgeFactor($fudgeFactor);
            $factor1 = $factor2 + $adj; // accumulate in $factor1
            $num1    = round($factor1 * -1) + $num2; // generate offset for $scramble1
            $num1    = self::_checkRange($num1); // check range
            $factor2 = $factor1 + $num2; // accumulate in $factor2
            // @formatter:on

            // Extract character from SCRAMBLE1 and append to $target string
            $char1 = substr(self::SCRAMBLE1, $num1, 1);
            $target .= $char1;
        }

        return isset($target) ? rtrim($target) : $target;
    }

    /**
     * Encrypt specified value.
     * @param string $key Value prepended to source to produce a more
     *        secure Encryption/
     * @param string $source Value to encrypt.
     * @param int $sourceLen Length of resulting encrypted value.
     * @return string Encrypted value.
     * @throws Exception if an error occurs.
     */
    public function encrypt(string $key, string $source, int $sourceLen = 0): string
    {
        if (empty($source)) {
            throw new Exception("No value has been supplied for Encryption");
        }

        str_pad($source, $sourceLen, " ");

        $key .= "" . strlen($source);
        $key = md5($key);

        // convert $key into a sequence of numbers. If error thrown
        // it will be passed on.
        $fudgeFactor = self::_convertKey($key);

        $target = null;
        $factor2 = 0;
        for ($idx = 0; $idx < strlen($source); $idx++) {
            $char1 = substr($source, $idx, 1);         // extract a character from $source
            $num1 = strpos(self::SCRAMBLE1, $char1); // identify its position in $scramble1
            if ($num1 === false) {
                throw new Exception("Source string contains an invalid character ($char1)");
            }

            // get an adjustment value using $fudgeFactor
            // @formatter:off
            $adj     = $this->_applyFudgeFactor($fudgeFactor);
            $factor1 = $factor2 + $adj;           // accumulate in $factor1
            $num2    = round($factor1) + $num1; // generate offset for $scramble2
            $num2    = self::_checkRange($num2); // check range
            $factor2 = $factor1 + $num2;          // accumulate in $factor2
            // @formatter:on

            // extract character from $scramble2
            $char2 = substr(self::SCRAMBLE2, $num2, 1);
            $target .= $char2;
        }

        return $target;
    }

    /**
     * getter of class property.
     * @return float Adjustment setting.
     */
    public function getAdjustment(): float
    {
        return $this->adj;
    }

    /**
     * getter of class property
     * @return int Modulus setting.
     */
    public function getModulus(): int
    {
        return $this->mod;
    }

    /**
     * setter for class property
     * @param float $adj New adjustment setting
     */
    public function setAdjustment(float $adj)
    {
        $this->adj = $adj;
    }

    /**
     * Set modulus value to use in encryption
     * @param int $mod New modulus value. Note that it will be
     *        made a positive integer.
     */
    public function setModulus($mod)
    {
        $this->mod = abs($mod); // must be a positive whole number
    }

    /**
     * Apply a specified fudge factor to the <b>adj</b> value.
     * @param array $fudgeFactor Array of fudge factor values.
     * @return float Adjustment after fudge factor applied.
     */
    private function _applyFudgeFactor(array &$fudgeFactor): float
    {
        $fudge = array_shift($fudgeFactor); // extract 1st number from array
        $fudge += $this->adj;               // add in adjustment value
        $fudgeFactor[] = $fudge;            // put it back at end of array

        // If the modifier has been supplied and the fudge value is evenly
        // divisible by it, negate the fudge value.
        if (!empty($this->mod)) {
            // if evenly divisible by modifier, negate it.
            if ($fudge % $this->mod == 0) {
                $fudge *= -1;
            }
        }

        return $fudge;
    }

    /**
     * Adjust number to be within calculated limit.
     * @param number $num Value to adjust as necessary.
     * @return number Adjusted value.
     */
    private static function _checkRange($num)
    {
        $num = round($num); // round up to nearest whole number

        // indexing starts at 0, not 1, so subtract 1 from string length
        $limit = strlen(self::SCRAMBLE1) - 1;
        while ($num > $limit) {
            $num -= $limit; // value too high, so reduce it
        }
        while ($num < 0) {
            $num += $limit; // value too low, so increase it
        }
        return $num;
    }

    /**
     * Convert the encryption key into the required form.
     * @param string $key Key value
     * @return array Converted key array.
     * @throws Exception if error occurs.
     */
    private static function _convertKey(string $key): array
    {
        if (empty($key)) {
            throw new Exception('No value has been supplied for the Encryption key');
        }

        $lclArray = [];
        $lclArray[] = strlen($key); // first entry in array is length of $key
        $tot = 0;

        for ($idx = 0; $idx < strlen($key); $idx++) {
            // extract a character from $key
            $char = substr($key, $idx, 1);
            // identify its position in $scramble1
            if (($num = strpos(self::SCRAMBLE1, $char)) === false) {
                throw new Exception("Key contains an invalid character ($char)");
            }

            $lclArray[] = $num; // store in output array
            $tot += $num; // accumulate total for later
        }

        $lclArray[] = $tot; // insert total as last entry in array
        return $lclArray;
    }
}
