<?php

namespace Inc\Claz;

use Swift_Attachment;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

use Exception;

use \Zend_Log;

/**
 * Class Email
 * @package Inc\Claz
 */
class Email
{
    protected string $bcc;
    protected string $body;
    protected string $format;
    protected string $from;
    protected string $fromFriendly;
    protected string $pdfFileName;
    protected string $pdfString;
    protected string $subject;
    protected string $emailTo;

    public function __construct()
    {
        $this->bcc = '';
        $this->body = '';
        $this->format = '';
        $this->from = '';
        $this->fromFriendly = '';
        $this->pdfFileName = '';
        $this->pdfString = '';
        $this->subject = '';
        $this->emailTo = '';
    }

    /**
     * @return array $results with indices of 'display_block' & 'refresh_redirect'
     */
    public function send()
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
            $refreshRedirect = "<meta http-equiv=\"refresh\" content=\"5;URL=index.php?module=invoices&amp;view=manage\" />";
            $displayBlock = "<div class=\"si_message_error\">{$message}</div>";
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
            $refreshRedirect = "<meta http-equiv=\"refresh\" content=\"5;URL=index.php?module=invoices&amp;view=manage\" />";
            $displayBlock = "<div class=\"si_message_error\">{$message}</div>";
            return [
                "message" => $message,
                "refresh_redirect" => $refreshRedirect,
                "display_block" => $displayBlock
            ];
        }

        $encryption = null;
        if (!empty($config->email->secure)) {
            $encryption = strtolower($config->email->secure);
            if ($encryption != 'tls' && $encryption != 'ssl') {
                $encryption = null;
            }
        }

        $transport = new Swift_SmtpTransport($config->email->host, $config->email->smtpport, $encryption);
        $transport->setUsername($config->email->username);
        $transport->setPassword($config->email->password);

        $mailer = new Swift_Mailer($transport);
        $message = new Swift_Message();

        // If pdfFileName is empty, pdfString is also empty per previous test.
        if (!empty($this->pdfFileName)) {
            $attachment = new Swift_Attachment($this->pdfString, $this->pdfFileName, 'application/pdf');
            $message->attach($attachment);
        }

        if (!empty($this->bcc)) {
            if (is_array($this->bcc)) {
                foreach ($this->bcc as $name) {
                    $message->addBcc($name);
                }
            } else {
                $message->setBcc([$this->bcc]);
            }
        }

        $message->setBody($this->body, 'text/html');

        if (empty($this->fromFriendly)) {
            $message->setFrom($this->from);
        } else {
            $message->setFrom([$this->from => $this->fromFriendly]);
        }

        $message->setSubject($this->subject);

        $message->setTo($this->emailTo);

        Log::out("Email::send() - Before Swift_Mailer send()", Zend_Log::DEBUG);
        $result = $mailer->send($message);

        Log::out("Email::send() - After Swift_Mailer send() result[{$result}]", Zend_Log::DEBUG);
        return self::makeResults($result);
    }

    /**
     * @param $result
     * @return array
     */
    private function makeResults($result)
    {
        switch ($this->format) {
            case "invoice":
                $refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";
                if ($result == 0) {
                    $message = $this->pdfFileName . "could not be sent";
                    $displayBlock = "<div class=\"si_message_error\">$message</div>";
                } else {
                    $message = $this->pdfFileName . " has been sent";
                    $displayBlock = "<div class=\"si_message_ok\">{$message}</div>";
                }
                break;

            case "statement":
                $refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=statement&amp;view=index\" />";
                if ($result == 0) {
                    $message = $this->pdfFileName . ' could not be sent';
                    $displayBlock = "<div class=\"si_message_error\">{$message}</div>";
                } else {
                    $message = $this->pdfFileName . ' has been sent';
                    $displayBlock = "<div class=\"si_message_ok\">{$message}</div>";
                }
                break;

            case "cron":
                $refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";
                if ($result == 0) {
                    $message = "Cron email for today has not been sent";
                    $displayBlock = "<div class=\"si_message_error\">{$message}</div>";
                } else {
                    $message = "Cron email for today has been sent";
                    $displayBlock = "<div class=\"si_message_ok\">{$message}</div>";
                }
                break;

            case "cron_invoice":
                $refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";
                if ($result == 0) {
                    $message = "Cron {$this->pdfFileName} has not been emailed";
                    $displayBlock = "<div class=\"si_message_error\">{$message}</div>";
                } else {
                    $message = "Cron {$this->pdfFileName} has been emailed";
                    $displayBlock = "<div class=\"si_message_ok\">{$message}</div>";
                }
                break;

            default:
                if (empty($this->format)) {
                    $this->format = ''; // Make sure empty is blank
                }
                $message = "Undefined format, \" . $this->format";
                error_log("Email::send() - {$message}");
                $refreshRedirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";
                $displayBlock = "<div class=\"si_message_error\">{$message}</div>";
        }

        return [
            "message" => $message,
            "refresh_redirect" => $refreshRedirect,
            "display_block" => $displayBlock
        ];
    }

    /**
     * Make a default subject for email based on specified type.
     * @param string $type
     * @return string
     */
    public function makeSubject($type)
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
                $message = "$this->pdfFileName from $this->fromFriendly";
                break;

        }

        return $message;
    }

    public function getBcc(): string
    {
        return $this->bcc;
    }

    /**
     * @param string $bcc
     * @return bool true if OK, false if not.
     */
    public function setBcc(string $bcc): bool
    {
        if (empty($bcc)) {
            $this->bcc = '';
        } else {
            $emailBcc = array_filter(explode(';', $bcc));
            if (is_array($emailBcc) && count($emailBcc) > 1) {
                foreach ($emailBcc as $addr) {
                    if (!filter_var($addr, FILTER_VALIDATE_EMAIL)) {
                        error_log("Email::setBcc() - Invalid BCC address in list[{$addr}]");
                        return false;
                    }
                }
                $this->bcc = $emailBcc;
            } else {
                if (!filter_var($bcc, FILTER_VALIDATE_EMAIL)) {
                    error_log("Email::setBcc() - Invalid BCC address[{$bcc}]");
                    return false;
                }
                $this->bcc = $bcc;
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
            $format != 'statement' &&
            $format != 'cron' &&
            $format != 'cron_invoice') {
            throw new Exception("Invalid format. Must be 'invoice', 'statement', 'cron' or 'cron_invoice'");
        }
        $this->format = $format;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from Valid email address of sender
     * @return bool true if OK, false of not.
     */
    public function setFrom(string $from): bool
    {
        if (empty($from) || !filter_var($from, FILTER_VALIDATE_EMAIL)) {
            error_log("Email::setFrom() - Invalid FROM address[{$from}]");
            return false;
        }

        $this->from = $from;
        return true;
    }

    public function getFromFriendly(): string
    {
        return $this->fromFriendly;
    }

    /**
     * @param string $fromFriendly Friendly name of sender
     */
    public function setFromFriendly(string $fromFriendly): void
    {
        $this->fromFriendly = $fromFriendly;
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

    public function getEmailTo(): string
    {
        return $this->emailTo;
    }

    /**
     * @param string $emailTo Email address to send message to.
     * @return bool true if OK, false if not.
     */
    public function setEmailTo(string $emailTo): bool
    {
        if (empty($emailTo)) {
            error_log("Email::setEmailTo() - Empty emailTo address");
            return false;
        } else {
            $emailTo = array_filter(explode(';', $emailTo));
            if (is_array($emailTo) && count($emailTo) > 1) {
                foreach ($emailTo as $addr) {
                    if (!filter_var($addr, FILTER_VALIDATE_EMAIL)) {
                        error_log("Email::setEmailTo() - Invalid emailTo address in list[{$addr}]");
                        return false;
                    }
                    $this->emailTo = $emailTo;
                }
            } else {
                if (is_array($emailTo)) {
                    $emailTo = $emailTo[0];
                }
                if (!filter_var($emailTo, FILTER_VALIDATE_EMAIL)) {
                    error_log("Email::setEmailTo() - Invalid emailTo address[{$emailTo}]");
                    return false;
                }
                $this->emailTo = $emailTo;
            }
        }
        return true;
    }

}
