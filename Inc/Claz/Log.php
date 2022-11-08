<?php /** @noinspection PhpPropertyOnlyWrittenInspection */

/** @noinspection PhpClassNamingConventionInspection */

namespace Inc\Claz;

use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * @name Log.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20181009
 */

/**
 * Class Log
 * @package Inc\Claz
 */
class Log extends Logger
{
    private static Logger $logger;
    private static string $folder = "";
    private static string $file = "";
    private static string $path = "";

    /**
     * Open the log file. One will be created if it doesn't exist.
     * @param string $level
     * @param string $folder
     * @param string $file
     */
    public static function open(string $level = "EMERGENCY", string $folder = "tmp/log/", string $file = "si.log")
    {
        if (!self::isOpen()) {
            // Create log file if it doesn't exist
            if (preg_match('/^.*\/$/', $folder) == 1) {
                self::$folder = $folder;
            } else {
                self::$folder = $folder . '/';
            }
            self::$path = self::$folder . $file;
            self::$file = $file;

            // Create file if it doesn't exist.
            if (!is_file(self::$path)) {
                /**
                 * @var resource|bool $fp
                 */
                $fp = fopen(self::$path, 'w');
                if ($fp === false) {
                    SiError::out('notWritable', 'folder', self::$folder);
                } else {
                    fclose($fp);
                }
            }

            // Assure file is writable
            if (!is_writable(self::$path)) {
                SiError::out('notWritable', 'file', self::$path);
            }

            switch ($level) {
                case 'DEBUG':
                    $loggerLevel = Logger::DEBUG;
                    break;

                case 'INFO':
                    $loggerLevel = Logger::INFO;
                    break;

                case 'NOTICE':
                    $loggerLevel = Logger::NOTICE;
                    break;

                case 'WARNING':
                    $loggerLevel = Logger::WARNING;
                    break;

                case 'ERROR':
                    $loggerLevel = Logger::ERROR;
                    break;

                case 'CRITICAL':
                    $loggerLevel = Logger::CRITICAL;
                    break;

                case 'ALERT':
                    $loggerLevel = Logger::ALERT;
                    break;

                case 'EMERGENCY':
                default:
                    $loggerLevel = Logger::EMERGENCY;
                    break;
            }

            // the default date format is "Y-m-d\TH:i:sP"
            $dateFormat = "Y-m-d, h:i a";
            // the default output format is "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
            $output = "%datetime% > %level_name% > %message% %context% %extra%\n";
            $formatter = new LineFormatter($output, $dateFormat, true, true);
            $stream = new StreamHandler(self::$path, $loggerLevel);
            $stream->setFormatter($formatter);
            self::$logger = new Logger('siLog');
            self::$logger->pushHandler($stream);
        }
    }

    /**
     * @param string $msg
     * @param int|null $level one of the following. The level used to open the logger sets the level for which messages
     *      send to Log::out() will be generated. This setting must be greater than or equal to the open level
     *      for a message to print. Ex: Log::open called with level Log::ERROR. Log::out called with level
     *      Log::WARNING. Log::WARNING less than Log::ERROR, so it does not print.
     *    DEBUG     (100): Detailed debug information. (Default if not specified)
     *    INFO      (200): Interesting events. Examples: User logs in, SQL logs.
     *    NOTICE    (250): Normal but significant events.
     *    WARNING   (300): Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
     *    ERROR     (400): Runtime errors that do not require immediate action but should typically be logged and monitored.
     *    CRITICAL  (500): Critical conditions. Example: Application component unavailable, unexpected exception.
     *    ALERT     (550): Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
     *    EMERGENCY (600): Emergency: system is unusable.
     */
    public static function out(string $msg, ?int $level = Logger::DEBUG)
    {
        if (!self::isOpen()) {
            global $config;
            self::open($config['loggerLevel']);
        }
        self::$logger->log($level, $msg);
    }

    /**
     * Check to see if logger session already open.
     * @return bool true is already open else false.
     */
    private static function isOpen(): bool
    {
        return isset(self::$logger);
    }

}
