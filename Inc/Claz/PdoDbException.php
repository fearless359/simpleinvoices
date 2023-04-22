<?php

namespace Inc\Claz;

use Exception;

/**
 * Class PdoDbException
 * @package Inc\Claz
 */
class PdoDbException extends Exception
{
    /**
     * PdoDbException constructor.
     * @param $message
     * @param int $code
     * @param Exception|null $previous
     */
    public function __construct($message, int $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        return __CLASS__ . ": [$this->code]: $this->message\n";
    }

    /**
     *
     * @noinspection PhpMethodMayBeStaticInspection
     * @noinspection PhpUnused
     */
    public function customFunctions()
    {
        echo "A custom function for this type of exception\n";
    }
}
