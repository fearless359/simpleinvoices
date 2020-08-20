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
    public function __construct($message, $code = 0, ?Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    /**
     *
     * @noinspection PhpMethodMayBeStaticInspection
     */
    public function customFunctions()
    {
        echo "A custom function for this type of exception\n";
    }
}