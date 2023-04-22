<?php

namespace Inc\Claz;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

use Exception;

/**
 * Class Email
 * @package Inc\Claz
 */
class Email
{
    protected array $bcc;
    protected string $body;
    protected string $format;
    protected array $from;
    protected string $pdfFileName;
    protected string $pdfString;
    protected string $subject;
    protected array $emailTo;

    public function __construct()
    {
        $this->bcc = [];
        $this->body = '';
        $this->format = '';
        $this->from = [];
        $this->pdfFileName = '';
        $this->pdfString = '';
        $this->subject = '';
        $this->emailTo = [];
    }

    /**
     *  @return void
     **/
    public function errorLog()
    {
        error_log("Email::errorLog() - bcc: " . print_r($this->bcc, true));
        error_log("Email::errorLog() - body[$this->body]");
        error_log("Email::errorLog() - emailTo: " . print_r($this->emailTo, true));
        error_log("Email::errorLog() - format[$this->format]");
        error_log("Email::errorLog() - from: " . print_r($this->from, true));
        error_log("Email::errorLog() - pdfFileName[$this->pdfFileName]");
        error_log("Email::errorLog() - subject[$this->subject]");

        // NOTE: pdfString not dumped on purpose.
    }

    /**
     * @return array $results with indices of 'display_block' & 'refresh_redirect'
     */
    public function send(): array
    {
        global $config;

        // Validate that minimum required fields are present
        // @formatter:off
        if (empty($this->body)    ||
            empty($this->format)  ||
            empty($this->from)    ||
            empty($this->subject) ||
            empty($this->emailTo)) {
            $message = "One or more required fields is missing";
            error_log("Email::send() - " . $message);
            $refreshRedirect = "<meta http-equiv='refresh' content='5;URL=index.php?module=invoices&amp;view=manage' />";
            $displayBlock = "<div class='si_message_error'>$message</div>";
            return [
                "message" => $message,
                "refresh_redirect" => $refreshRedirect,
                "display_block" =>$displayBlock
            ];
        }
        // @formatter:on

        if (!empty($this->pdfString) && empty($this->pdfFileName) ||
            empty($this->pdfString) && !empty($this->pdfFileName)) {
            $message = "Both pdfString and pdfFileName must be set or left empty";
            error_log("Email::send() - " . $message);
            $refreshRedirect = "<meta http-equiv='refresh' content='5;URL=index.php?module=invoices&amp;view=manage' />";
            $displayBlock = "<div class='si_message_error'>$message</div>";
            return [
                "message"          => $message,
                "refresh_redirect" => $refreshRedirect,
                "display_block"    => $displayBlock
            ];
        }

        $encryption = null;
        if (!empty($config['emailSecure'])) {
            $encryption = strtolower($config['emailSecure']);
            if ($encryption != 'tls' && $encryption != 'ssl') {
                $encryption = null;
            }
        }

        $transport = new Swift_SmtpTransport($config['emailHost'], $config['emailSmtpPort'], $encryption);
        $transport->setUsername($config['emailUsername']);
        $transport->setPassword($config['emailPassword']);

        $mailer = new Swift_Mailer($transport);
        $message = new Swift_Message();

        // If pdfFileName is empty, pdfString is also empty per previous test.
        if (!empty($this->pdfFileName)) {
            $attachment = new Swift_Attachment($this->pdfString, $this->pdfFileName, 'application/pdf');
            $message->attach($attachment);
        }

        if (!empty($this->bcc)) {
            foreach ($this->bcc as $emailAddr) {
                $message->addBcc($emailAddr);
            }
        }

        $message->setBody($this->body, 'text/html');

        foreach ($this->from as $key => $value) {
            if (is_int($key)) {
                $message->addFrom($value);
            } else {
                $message->addFrom($key, $value);
            }
        }

        $message->setSubject($this->subject);

        foreach ($this->emailTo as $key => $value) {
            if (is_int($key)) {
                $message->addTo($value);
            } else {
                $message->addTo($key, $value);
            }
        }

        Log::out("Email::send() - Before Swift_Mailer send()");
        $result = $mailer->send($message);

        Log::out("Email::send() - After Swift_Mailer send() result[$result]");
        return self::makeResults($result);
    }

    private function makeResults(int $result): array
    {
        switch ($this->format) {
            case "invoice":
                $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=invoices&amp;view=manage' />";
                if ($result == 0) {
                    $message = $this->pdfFileName . "could not be sent";
                    $displayBlock = "<div class='si_message_error'>$message</div>";
                } else {
                    $message = $this->pdfFileName . " has been sent";
                    $displayBlock = "<div class='si_message_ok'>$message</div>";
                }
                break;

            case "reports":
                $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=reports&amp;view=index' />";
                if ($result == 0) {
                    $message = $this->pdfFileName . ' could not be sent';
                    $displayBlock = "<div class='si_message_error'>$message</div>";
                } else {
                    $message = $this->pdfFileName . ' has been sent';
                    $displayBlock = "<div class='si_message_ok'>$message</div>";
                }
                break;

            case "statement":
                $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=statement&amp;view=index' />";
                if ($result == 0) {
                    $message = $this->pdfFileName . ' could not be sent';
                    $displayBlock = "<div class='si_message_error'>$message</div>";
                } else {
                    $message = $this->pdfFileName . ' has been sent';
                    $displayBlock = "<div class='si_message_ok'>$message</div>";
                }
                break;

            case "cron":
                $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=invoices&amp;view=manage' />";
                if ($result == 0) {
                    $message = "Cron email for today has not been sent";
                    $displayBlock = "<div class='si_message_error'>$message</div>";
                } else {
                    $message = "Cron email for today has been sent";
                    $displayBlock = "<div class='si_message_ok'>$message</div>";
                }
                break;

            case "cron_invoice":
                $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=invoices&amp;view=manage' />";
                if ($result == 0) {
                    $message = "Cron $this->pdfFileName has not been emailed";
                    $displayBlock = "<div class='si_message_error'>$message</div>";
                } else {
                    $message = "Cron $this->pdfFileName has been emailed";
                    $displayBlock = "<div class='si_message_ok'>$message</div>";
                }
                break;

            default:
                if (empty($this->format)) {
                    $this->format = ''; // Make sure empty is blank
                }
                $message = "Undefined format, \" . $this->format";
                error_log("Email::send() - $message");
                $refreshRedirect = "<meta http-equiv='refresh' content='2;URL=index.php?module=invoices&amp;view=manage' />";
                $displayBlock = "<div class='si_message_error'>$message</div>";
        }

        return [
            "message"          => $message,
            "refresh_redirect" => $refreshRedirect,
            "display_block"    => $displayBlock
        ];
    }

    /**
     * Make a default subject for email based on specified type.
     * @param string $type
     * @return string
     */
    public function makeSubject(string $type): string
    {
        switch ($type) {
            case "invoice_eway":
                $message = "$this->pdfFileName ready for automatic credit card payment";
                break;

            case "invoice_eway_receipt":
                $message = "$this->pdfFileName secure credit card payment successful";
                break;

            case "invoice_receipt":
                $message = "$this->pdfFileName has been emailed";
                break;

            case "invoice":
            default:
                $key = array_key_first($this->from);
                if (is_int($key)) {
                    $friendly = '';
                } else {
                    $friendly = $this->from[$key];
                }
                $message = "$this->pdfFileName from $friendly";
                break;

        }

        return $message;
    }

    public function getBcc(): array
    {
        return $this->bcc;
    }

    /**
     * Validate the BCC email addresses and add them to the bcc property. This method can
     * be called multiple times if necessary.
     * @param array $bcc Array constructed with one or more email addresses and optional
     *                   friendly name. If a friendly name is present then the bcc email
     *                   address becomes the index key and the value is the friendly name.
     *                   Otherwise, you have an int index with the value being the bcc
     *                   email address. Example:
     *                   ["bccEmailAddr@gmail.com" => "friendlyName", "anotherBccEmailAddr@gmail.com"]
     * @return bool true if OK, false if not.
     */
    public function setBcc(array $bcc): bool
    {
        if (empty($bcc)) {
            error_log("Email::setEmailTo() - Empty emailTo address");
            return false;
        }

        $validator = new EmailValidator();
        foreach ($bcc as $key => $value) {
            if (is_int($key)) {
                // An "explode" of an empty string generates an array with a 0 index and blank value.
                // So check for value here to make sure it doesn't sneak by us.
                if (empty($value)) {
                    continue;
                }
                $bccAddr = $value;
                $bccFriendly = '';
            } else {
                $bccAddr = $key;
                $bccFriendly = $value;
            }

            if (!$validator->isValid($bccAddr, new RFCValidation())) {
                error_log("Email::setBcc() - Invalid emailTo address in list[$bccAddr]");
                return false;
            }

            if (!empty($bccFriendly)) {
                $this->bcc[$bccAddr] = $bccFriendly;
            } else {
                $this->bcc[] = $bccAddr;
            }
        }

        return true;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param string $format
     * @throws Exception
     */
    public function setFormat(string $format): void
    {
        if ($format != 'invoice' &&
            $format != 'reports' &&
            $format != 'statement' &&
            $format != 'cron' &&
            $format != 'cron_invoice') {
            throw new Exception("Invalid format. Must be 'invoice', 'statement', 'cron' or 'cron_invoice'");
        }
        $this->format = $format;
    }

    public function getFrom(): array
    {
        return $this->from;
    }

    /**
     * Validate the FROM email addresses and add them the FROM property. This method can
     * be called multiple times if necessary.
     * @param array $from Array constructed with one or more email addresses and optional an
     *                    friendly name. If a friendly name is present then the FROM
     *                    address becomes the index key and the value is the friendly name.
     *                    Otherwise, you have an int index with the value being the FROM
     *                    address. Example:
     *                    ["fromAddr@gmail.com" => "friendlyName", "anotherFromAddr@gmail.com"]
     * @return bool true if OK, false if not.
     */
    public function setFrom(array $from): bool
    {
        if (empty($from)) {
            error_log("Email::setFrom() - Empty from address");
            return false;
        }

        $validator = new EmailValidator();
        foreach ($from as $key => $value) {
            if (is_int($key)) {
                // An "explode" of an empty string generates an array with a 0 index and blank value.
                // So check for value here to make sure it doesn't sneak by us.
                if (empty($value)) {
                    continue;
                }
                $fromAddr = $value;
                $fromFriendly = '';
            } else {
                $fromAddr = $key;
                $fromFriendly = $value;
            }
            if (!$validator->isValid($fromAddr, new RFCValidation())) {
                error_log("Email::setFrom() - Invalid from address in list[$fromAddr]");
                return false;
            }

            if (!empty($fromFriendly)) {
                $this->from[$fromAddr] = $fromFriendly;
            } else {
                $this->from[] = $fromAddr;
            }
        }

        return true;
    }

    public function getPdfFileName(): string
    {
        return $this->pdfFileName;
    }

    public function setPdfFileName(string $pdfFileName): void
    {
        $this->pdfFileName = $pdfFileName;
    }

    public function getPdfString(): string
    {
        return $this->pdfString;
    }

    /**
     * @param string $pdfString PDF file in string format
     */
    public function setPdfString(string $pdfString): void
    {
        $this->pdfString = $pdfString;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(string $subject): void
    {
        $this->subject = $subject;
    }

    public function getEmailTo(): array
    {
        return $this->emailTo;
    }

    /**
     * Validate the TO email addresses and add them to the emailTo property. This method can
     * be called multiple times if necessary.
     * @param array $emailTo Array constructed with one or more email addresses and optional
     *                       friendly name. If a friendly name is present then the emailTo
     *                       address becomes the index key and the value is the friendly name.
     *                       Otherwise, you have an int index with the value being the emailTo
     *                       address. Example:
     *                       ["emailToAddr@gmail.com" => "friendlyName", "anotherEmailToAddr@gmail.com"]
     * @return bool true if OK, false if not.
     */
    public function setEmailTo(array $emailTo): bool
    {
        if (empty($emailTo)) {
            error_log("Email::setEmailTo() - Empty emailTo address");
            return false;
        }

        $validator = new EmailValidator();
        foreach ($emailTo as $key => $value) {
            if (is_int($key)) {
                // An "explode" of an empty string generates an array with a 0 index and blank value.
                // So check for value here to make sure it doesn't sneak by us.
                if (empty($value)) {
                    continue;
                }
                $toAddr = $value;
                $toFriendly = '';
            } else {
                $toAddr = $key;
                $toFriendly = $value;
            }
            if (!$validator->isValid($toAddr, new RFCValidation())) {
                error_log("Email::setEmailTo() - Invalid emailTo address in list[$toAddr]");
                return false;
            }

            if (!empty($toFriendly)) {
                $this->emailTo[$toAddr] = $toFriendly;
            } else {
                $this->emailTo[] = $toAddr;
            }
        }

        return true;
    }

}
