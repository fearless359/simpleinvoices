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
class Email {
    protected $bcc;
    protected $body;
    protected $format;
    protected $from;
    protected $from_friendly;
    protected $pdf_file_name;
    protected $pdf_string;
    protected $subject;
    protected $to;

    public function __construct()
    {
        $this->bcc = '';
        $this->body = '';
        $this->format = '';
        $this->from = '';
        $this->from_friendly = '';
        $this->pdf_file_name = '';
        $this->pdf_string = '';
        $this->subject = '';
        $this->to = '';
    }

    /**
     * @return array $results with indices of 'display_block' & 'refresh_redirect'
     */
    public function send() {
        global $config;

        // Validate that minimum required fields are present
        // @formatter:off
        if (empty($this->body)    ||
            empty($this->format)  ||
            empty($this->from)    ||
            empty($this->subject) ||
            empty($this->to)) {
            $message = "One or more required fields is missing";
            error_log("Email::send() - " . $message);
            $refresh_redirect = "<meta http-equiv=\"refresh\" content=\"5;URL=index.php?module=invoices&amp;view=manage\" />";
            $display_block = "<div class=\"si_message_error\">{$message}</div>";
            $results = [
                "message" => $message,
                "refresh_redirect" => $refresh_redirect,
                "display_block" =>$display_block
            ];
            return $results;
        }
        // @formatter:on

        if ((!empty($this->pdf_string) &&  empty($this->pdf_file_name)) ||
            ( empty($this->pdf_string) && !empty($this->pdf_file_name))) {
            $message = "Both pdf_string and pdf_file_name must be set or left empty";
            error_log("Email::send() - " . $message);
            $refresh_redirect = "<meta http-equiv=\"refresh\" content=\"5;URL=index.php?module=invoices&amp;view=manage\" />";
            $display_block = "<div class=\"si_message_error\">{$message}</div>";
            $results = [
                "message" => $message,
                "refresh_redirect" => $refresh_redirect,
                "display_block" =>$display_block
            ];
            return $results;
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

        // If pdf_file_name is empty, pdf_string is also empty per previous test.
        if (!empty($this->pdf_file_name)) {
            $attachment = new Swift_Attachment($this->pdf_string, $this->pdf_file_name, 'application/pdf');
            $message->attach($attachment);
        }

        if (!empty($this->bcc)) {
            if (is_array($this->bcc)) {
                foreach($this->bcc as $name) {
                    $message->addBcc($name);
                }
            } else {
                $message->setBcc([$this->bcc]);
            }
        }

        $message->setBody($this->body, 'text/html');

        if (empty($this->from_friendly)) {
            $message->setFrom($this->from);
        } else {
            $message->setFrom([$this->from => $this->from_friendly]);
        }

        $message->setSubject($this->subject);

        $message->setTo($this->to);

        Log::out("Email::send() - Before Swift_Mailer send()", Zend_Log::DEBUG);
        $result = $mailer->send($message);

        Log::out("Email::send() - After Swift_Mailer send() result[{$result}]", Zend_Log::DEBUG);
        $results = self::makeResults($result);
        return $results;
    }

    /**
     * @param $result
     * @return array
     */
    private function makeResults($result) {
        switch ($this->format) {
            case "invoice":
                $refresh_redirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";
                if ($result == 0) {
                    $message = $this->pdf_file_name . "could not be sent";
                    $display_block = "<div class=\"si_message_error\">$message</div>";
                } else {
                    $message = $this->pdf_file_name . " has been sent";
                    $display_block = "<div class=\"si_message_ok\">{$message}</div>";
                }
                break;

            case "statement":
                $refresh_redirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=statement&amp;view=index\" />";
                if ($result == 0) {
                    $message = $this->pdf_file_name . ' could not be sent';
                    $display_block = "<div class=\"si_message_error\">{$message}</div>";
                } else {
                    $message = $this->pdf_file_name . ' has been sent';
                    $display_block = "<div class=\"si_message_ok\">{$message}</div>";
                }
                break;

            case "cron":
                $refresh_redirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";
                if ($result == 0) {
                    $message = "Cron email for today has not been sent";
                    $display_block = "<div class=\"si_message_error\">{$message}</div>";
                } else {
                    $message = "Cron email for today has been sent";
                    $display_block = "<div class=\"si_message_ok\">{$message}</div>";
                }
                break;

            case "cron_invoice":
                $refresh_redirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";
                if ($result == 0) {
                    $message = "Cron {$this->pdf_file_name} has not been emailed";
                    $display_block = "<div class=\"si_message_error\">{$message}</div>";
                } else {
                    $message = "Cron {$this->pdf_file_name} has been emailed";
                    $display_block = "<div class=\"si_message_ok\">{$message}</div>";
                }
                break;

            default:
                if (empty($this->format)) {
                    $this->format = ''; // Make sure empty is blank
                }
                $message = "Undefined format, \" . $this->format";
                error_log("Email::send() - {$message}");
                $refresh_redirect = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\" />";
                $display_block = "<div class=\"si_message_error\">{$message}</div>";
        }
        $results = [
            "message" => $message,
            "refresh_redirect" => $refresh_redirect,
            "display_block" =>$display_block
        ];
        return $results;
    }
    /**
     * Make a default subject for email based on specified type.
     * @param string $type
     * @return string
     */
    public function makeSubject($type) {
        switch ($type) {
            case "invoice_eway":
                $message = "$this->pdf_file_name ready for automatic credit card payment";
                break;

            case "invoice_eway_receipt":
                $message = "$this->pdf_file_name secure credit card payment successful";
                break;

            case "invoice_receipt":
                $message = "$this->pdf_file_name has been emailed";
                break;

            case "invoice":
            default:
                $message = "$this->pdf_file_name from $this->from_friendly";
                break;

        }

        return $message;
    }

    /**
     * @return string
     */
    public function getBcc(): string
    {
        return $this->bcc;
    }

    /**
     * @param mixed $bcc
     * @return bool true if OK, false if not.
     */
    public function setBcc($bcc): bool
    {
        if (empty($bcc)) {
            $this->bcc = '';
        } else {
            $email_bcc = array_filter(explode(';', $bcc));
            if (is_array($email_bcc) && count($email_bcc) > 1) {
                foreach ($email_bcc as $addr) {
                    if (!filter_var($addr, FILTER_VALIDATE_EMAIL)) {
                        error_log("Email::setBcc() - Invalid BCC address in list[{$addr}]");
                        return false;
                    }
                }
                $this->bcc = $email_bcc;
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

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body): void
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getFormat(): string
    {
        return $this->format;
    }

    /**
     * @param mixed $format
     * @throws Exception
     */
    public function setFormat($format): void
    {
        if ($format != 'invoice' &&
            $format != 'statement' &&
            $format != 'cron' &&
            $format != 'cron_invoice') {
            throw new Exception("Invalid format. Must be 'invoice', 'statement', 'cron' or 'crong_invoice'");
        }
        $this->format = $format;
    }

    /**
     * @return string
     */
    public function getFrom(): string
    {
        return $this->from;
    }

    /**
     * @param string $from Valid email address of sender
     * @return bool true if OK, false of not.
     */
    public function setFrom($from): bool
    {
        if (empty($from) || !filter_var($from, FILTER_VALIDATE_EMAIL)) {
            error_log("Email::setFrom() - Invalid FROM address[{$from}]");
            return false;
        }

        $this->from = $from;
        return true;
    }

    /**
     * @return string
     */
    public function getFromFriendly(): string
    {
        return $this->from_friendly;
    }

    /**
     * @param string $from_friendly Friendly name of sender
     */
    public function setFromFriendly($from_friendly): void
    {
        $this->from_friendly = $from_friendly;
    }

    /**
     * @return string
     */
    public function getPdfFileName(): string
    {
        return $this->pdf_file_name;
    }

    /**
     * @param string $pdf_file_name
     */
    public function setPdfFileName($pdf_file_name): void
    {
        $this->pdf_file_name = $pdf_file_name;
    }

    /**
     * @return string
     */
    public function getPdfString(): string
    {
        return $this->pdf_string;
    }

    /**
     * @param string $pdf_string PDF file in string format
     */
    public function setPdfString($pdf_string): void
    {
        $this->pdf_string = $pdf_string;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject): void
    {
        $this->subject = $subject;
    }

    /**
     * @return string
     */
    public function getTo(): string
    {
        return $this->to;
    }

    /**
     * @param array/string $to Email address to send message to.
     * @return bool true if OK, false if not.
     */
    public function setTo($to): bool
    {
        if (empty($to)) {
            error_log("Email::setTo() - Empty TO address");
            return false;
        } else {
            $email_to = array_filter(explode(';', $to));
            if (is_array($email_to) && count($email_to) > 1) {
                foreach ($email_to as $addr) {
                    if (!filter_var($addr, FILTER_VALIDATE_EMAIL)) {
                        error_log("Email::setTo() - Invalid TO address in list[{$addr}]");
                        return false;
                    }
                    $this->to = $email_to;
                }
            } else {
                if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
                    error_log("Email::setTo() - Invalid TO address[{$to}]");
                    return false;
                }
                $this->to = $to;
            }
        }
        return true;
    }

}
