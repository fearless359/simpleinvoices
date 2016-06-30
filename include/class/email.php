<?php
class email {
    public $format;
    public $notes;
    public $file_location;
    public $attachment;
    public $id;
    public $start_date;
    public $end_date;
    public $biller_id;
    public $customer_id;
    public $domain_id;

    function send() {
        global $config;

        // Create authentication with SMTP server
        $authentication = array();
        if ($config->email->smtp_auth) {
            // @formatter:off
            $authentication = array('auth'     => 'login',
                                    'username' => $config->email->username,
                                    'password' => $config->email->password,
                                    'ssl'      => $config->email->secure,
                                    'port'     => $config->email->smtpport);
            // @formatter:on
        }

        if (!$config->email->use_local_sendmail) {
            $transport = new Zend_Mail_Transport_Smtp($config->email->host, $authentication);
        }

        // Create e-mail message
        $mail = new Zend_Mail('utf-8');
        $mail->setType(Zend_Mime::MULTIPART_MIXED);
        $mail->setBodyText($this->notes);
        $mail->setBodyHTML($this->notes);
        $mail->setFrom($this->from, $this->from_friendly);

        $to_addresses = preg_split('/\s*[,;]\s*/', $this->to);
        if (!empty($to_addresses)) {
            foreach ($to_addresses as $to) {
                $mail->addTo($to);
            }
        }

        if (!empty($this->bcc)) {
            $bcc_addresses = preg_split('/\s*[,;]\s*/', $this->bcc);
            foreach ($bcc_addresses as $bcc) {
                $mail->addBcc($bcc);
            }
        }
        $mail->setSubject($this->subject);

        if ($this->attachment) {
            // Create attachment
            $content = file_get_contents('./tmp/cache/' . $this->attachment);
            $at = $mail->createAttachment($content);
            $at->type = 'application/pdf';
            $at->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
            $at->filename = $this->attachment;
        }
        // Send e-mail through SMTP
        try {
            if ($config->email->use_local_sendmail) {
                $mail->send();
            } else {
                $mail->send($transport);
            }
        } catch (Zend_Mail_Protocol_Exception $e) {
            echo '<strong>Zend Mail Protocol Exception:</strong> ' . $e->getMessage();
            error_log("email.php mail error - " . print_r($e,true));
            exit();
        }

        // Remove temp invoice
        unlink("./tmp/cache/$this->attachment");

        switch ($this->format) {
            case "invoice":
                // Create succes message
                $message = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=invoices&amp;view=manage\">";
                $message .= "<br />$this->attachment has been emailed";
                break;

            case "statement":
                // Create succes message
                $message = "<meta http-equiv=\"refresh\" content=\"2;URL=index.php?module=statement&amp;view=index\">";
                $message .= "<br />$this->attachment has been emailed";
                break;

            case "cron":
                // Create succes message
                $message .= "<br />Cron email for today has been sent";
                break;

            case "cron_invoice":
                // Create succes message
                $message .= "$this->attachment has been emailed";
                break;

            default:
                echo '<strong>Undefined format (' . $this->format . ')</strong>';
                exit();
        }

        return $message;
    }

    public function set_subject($type = '') {
        switch ($type) {
            case "invoice_eway":
                $message = "$this->invoice_name ready for automatic credit card payment";
                break;

            case "invoice_eway_receipt":
                $message = "$this->invoice_name secure credit card payment successful";
                break;

            case "invoice_receipt":
                $message = "$this->attachment has been emailed";
                break;

            case "invoice":
            default:
                $message = "$this->attachment from $this->from_friendly";
                break;

        }

        return $message;
    }

    public function get_admin_email() {
        $domain_id = domain_id::get($this->domain_id);

        // @formatter:off
        $sql = "SELECT u.email " .
               "FROM " . TB_PREFIX . "user u " .
               "LEFT JOIN " . TB_PREFIX . "user_role r ON (u.role_id = r.id) " .
               "WHERE r.name = administrator AND domain_id = :domain_id " .
               "LIMIT 1";
        // @formatter:on
        $sth = dbQuery($sql, ':domain_id', $domain_id);

        return $sth->fetchColumn();
    }
}
