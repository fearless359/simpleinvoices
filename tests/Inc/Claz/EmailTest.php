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
        static::assertEmpty($this->email->getBcc(), 'bcc field is not empty');
        static::assertEmpty($this->email->getBody(), 'body field is not empty');
        static::assertEmpty($this->email->getFormat(), 'format field is not empty');
        static::assertEmpty($this->email->getFrom(), 'from field is not empty');
        static::assertEmpty($this->email->getFromFriendly(), 'from_friendly field is not empty');
        static::assertEmpty($this->email->getPdfFileName(), 'pdf_file_name field is not empty');
        static::assertEmpty($this->email->getPdfString(), 'pdf_string field is not empty');
        static::assertEmpty($this->email->getSubject(), 'subject field is not empty');
        static::assertEmpty($this->email->getEMailTo(), 'to field is not empty');
    }

    public function testMakeSubject()
    {
        $this->email->setPdfFileName("filename.pdf");
        $this->email->setFromFriendly("Richard Rowley");

        static::assertEquals("filename.pdf ready for automatic credit card payment",
            $this->email->makeSubject('invoice_eway'));

        static::assertEquals("filename.pdf secure credit card payment successful",
            $this->email->makeSubject('invoice_eway_receipt'));

        static::assertEquals("filename.pdf has been emailed",
            $this->email->makeSubject('invoice_receipt'));

        static::assertEquals("filename.pdf from Richard Rowley",
            $this->email->makeSubject('invoice'));
    }


    public function testSend()
    {
        $results = $this->email->send();
        static::assertEquals("One or more required fields is missing", $results['message']);

        $this->email->setBody("This is the message body");
        try {
            $this->email->setFormat("invoice");
        } catch (Exception $exp) {
            static::assertTrue(false, "Unexpected exception thrown by setFormat. Error: {$exp->getMessage()}");
        }
        $this->email->setFrom("from@gmail.com");
        $this->email->setSubject("Email subject");
        $this->email->setEmailTo("to@gmail.com");
        $this->email->setPdfString("PDF string");
        $results = $this->email->send();
        static::assertEquals("Both pdf_string and pdf_file_name must be set or left empty", $results['message']);

        // Note, no further testing of this method deemed necessary.
    }

    public function testSetGetBody()
    {
        $this->email->setBody("This is the body");
        static::assertEquals("This is the body", $this->email->getBody());
    }

    public function testSetGetFrom()
    {
        $result = $this->email->setFrom("me@gmail.com");
        static::assertTrue($result, "testSetGetFrom setFrom for valid email returned a false result");
        static::assertEquals("me@gmail.com", $this->email->getFrom());

        $result = $this->email->setFrom("invalid_email_address");
        static::assertFalse($result, "testSetGetFrom setFrom for invalid email returned a true result");
    }

    public function testSetGetFormat()
    {
        try {
            $this->email->setFormat("invoice");
            static::assertEquals("invoice", $this->email->getFormat());
        } catch (Exception $exp) {
            static::assertTrue(false, "testSetGetFormat setFormat for invoice throws exception {$exp->getMessage()}");
        }
        try {
            $this->email->setFormat("statement");
            static::assertEquals("statement", $this->email->getFormat());
        } catch (Exception $exp) {
            static::assertTrue(false, "testSetGetFormat setFormat for statement throws exception {$exp->getMessage()}");
        }
        try {
            $this->email->setFormat("cron");
            static::assertEquals("cron", $this->email->getFormat());
        } catch (Exception $exp) {
            static::assertTrue(false, "testSetGetFormat setFormat for cron throws exception {$exp->getMessage()}");
        }
        try {
            $this->email->setFormat("cron_invoice");
            static::assertEquals("cron_invoice", $this->email->getFormat());
        } catch (Exception $exp) {
            static::assertTrue(false, "testSetGetFormat setFormat for cron_invoice throws exception {$exp->getMessage()}");
        }

        try {
            static::expectExceptionMessage("Invalid format. Must be 'invoice', 'statement', 'cron' or 'cron_invoice'");
            $this->email->setFormat("");
        } catch (Exception $exp) {
            static::assertTrue(false, "testSetGetFormat setFormat for empty value throws exception {$exp->getMessage()}");
        }
    }

    public function testSetGetPdfFileName()
    {
        $this->email->setPdfFileName("my_filename.pdf");
        static::assertEquals("my_filename.pdf", $this->email->getPdfFileName());
    }

    public function testSetGetFromFriendly()
    {
        $this->email->setFromFriendly("Richard Rowley");
        static::assertEquals("Richard Rowley", $this->email->getFromFriendly());
    }

    public function testSetGetBcc()
    {
        $this->email->setBcc("me@gmail.com");
        static::assertEquals("me@gmail.com", $this->email->getBcc());

        $this->expectExceptionMessage("Invalid 'bcc' email address specified");
        $this->email->setBcc("invalid_email_address");
    }

    public function testSetGetTo()
    {
        $this->email->setEmailTo("me@gmail.com");
        static::assertEquals("me@gmail.com", $this->email->getEmailTo());

        $this->expectExceptionMessage("Invalid 'to' email address specified");
        $this->email->setEmailTo("invalid_email_address");

    }
}
