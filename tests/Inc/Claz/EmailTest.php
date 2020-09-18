<?php
namespace Inc\Claz;

use Exception;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailTest
 * @package Inc\Claz
 */
class EmailTest extends TestCase
{
    protected Email $email;
    
    public function setUp()
    {
        parent::setUp();
        $this->email = new Email();
    }

    public function testConstruct()
    {
        self::assertEmpty($this->email->getBcc(), 'bcc field is not empty');
        self::assertEmpty($this->email->getBody(), 'body field is not empty');
        self::assertEmpty($this->email->getFormat(), 'format field is not empty');
        self::assertEmpty($this->email->getFrom(), 'from field is not empty');
        self::assertEmpty($this->email->getFromFriendly(), 'from_friendly field is not empty');
        self::assertEmpty($this->email->getPdfFileName(), 'pdf_file_name field is not empty');
        self::assertEmpty($this->email->getPdfString(), 'pdf_string field is not empty');
        self::assertEmpty($this->email->getSubject(), 'subject field is not empty');
        self::assertEmpty($this->email->getEMailTo(), 'to field is not empty');
    }

    public function testMakeSubject()
    {
        $this->email->setPdfFileName("filename.pdf");
        $this->email->setFromFriendly("Richard Rowley");

        self::assertEquals("filename.pdf ready for automatic credit card payment",
            $this->email->makeSubject('invoice_eway'));

        self::assertEquals("filename.pdf secure credit card payment successful",
            $this->email->makeSubject('invoice_eway_receipt'));

        self::assertEquals("filename.pdf has been emailed",
            $this->email->makeSubject('invoice_receipt'));

        self::assertEquals("filename.pdf from Richard Rowley",
            $this->email->makeSubject('invoice'));
    }


    public function testSend()
    {
        $results = $this->email->send();
        self::assertEquals("One or more required fields is missing", $results['message']);

        $this->email->setBody("This is the message body");
        try {
            $this->email->setFormat("invoice");
        } catch (Exception $exp) {
            self::assertTrue(false, "Unexpected exception thrown by setFormat. Error: {$exp->getMessage()}");
        }
        $this->email->setFrom("from@gmail.com");
        $this->email->setSubject("Email subject");
        $this->email->setEmailTo("to@gmail.com");
        $this->email->setPdfString("PDF string");
        $results = $this->email->send();
        self::assertEquals("Both pdfString and pdfFileName must be set or left empty", $results['message']);

        // Note, no further testing of this method deemed necessary.
    }

    public function testSetGetBody()
    {
        $this->email->setBody("This is the body");
        self::assertEquals("This is the body", $this->email->getBody());
    }

    public function testSetGetFrom()
    {
        $result = $this->email->setFrom("me@gmail.com");
        self::assertTrue($result, "testSetGetFrom setFrom for valid email returned a false result");
        self::assertEquals("me@gmail.com", $this->email->getFrom());

        $result = $this->email->setFrom("invalid_email_address");
        self::assertFalse($result, "testSetGetFrom setFrom for invalid email returned a true result");
    }

    public function testSetGetFormat()
    {
        try {
            $this->email->setFormat("invoice");
            self::assertEquals("invoice", $this->email->getFormat());
        } catch (Exception $exp) {
            self::assertTrue(false, "testSetGetFormat setFormat for invoice throws exception {$exp->getMessage()}");
        }
        try {
            $this->email->setFormat("statement");
            self::assertEquals("statement", $this->email->getFormat());
        } catch (Exception $exp) {
            self::assertTrue(false, "testSetGetFormat setFormat for statement throws exception {$exp->getMessage()}");
        }
        try {
            $this->email->setFormat("cron");
            self::assertEquals("cron", $this->email->getFormat());
        } catch (Exception $exp) {
            self::assertTrue(false, "testSetGetFormat setFormat for cron throws exception {$exp->getMessage()}");
        }
        try {
            $this->email->setFormat("cron_invoice");
            self::assertEquals("cron_invoice", $this->email->getFormat());
        } catch (Exception $exp) {
            self::assertTrue(false, "testSetGetFormat setFormat for cron_invoice throws exception {$exp->getMessage()}");
        }

        self::expectExceptionMessage("Invalid format. Must be 'invoice', 'statement', 'cron' or 'cron_invoice'");
        $this->email->setFormat("");
    }

    public function testSetGetPdfFileName()
    {
        $this->email->setPdfFileName("my_filename.pdf");
        self::assertEquals("my_filename.pdf", $this->email->getPdfFileName());
    }

    public function testSetGetFromFriendly()
    {
        $this->email->setFromFriendly("Richard Rowley");
        self::assertEquals("Richard Rowley", $this->email->getFromFriendly());
    }

    public function testSetGetBcc()
    {
        $this->email->setBcc("me@gmail.com");
        self::assertEquals("me@gmail.com", $this->email->getBcc());

        self::assertFalse($this->email->setBcc("invalid_email_address"), "Expected failed setBcc() call.");
    }

    public function testSetEmailTo()
    {
        $this->email->setEmailTo("me@gmail.com';you@gmail.com");
        self::assertEquals("me@gmail.com;you@gmail.com", $this->email->getEmailTo());

        self::assertFalse($this->email->setEmailTo("invalid_email_address"), "Expected failed setEmailTo() call.");

    }
}
