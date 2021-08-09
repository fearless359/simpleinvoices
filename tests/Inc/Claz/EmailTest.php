<?php
namespace Inc\Claz;

use Exception;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

/**
 * Class EmailTest
 * @package Inc\Claz
 */
class EmailTest extends TestCase
{
    protected Email $email;
    
    public function setUp(): void
    {
        parent::setUp();
        $this->email = new Email();
    }

    public function testConstruct()
    {
        Assert::assertEmpty($this->email->getBcc(), 'bcc field is not empty');
        Assert::assertEmpty($this->email->getBody(), 'body field is not empty');
        Assert::assertEmpty($this->email->getFormat(), 'format field is not empty');
        Assert::assertEmpty($this->email->getFrom(), 'from field is not empty');
        Assert::assertEmpty($this->email->getFromFriendly(), 'from_friendly field is not empty');
        Assert::assertEmpty($this->email->getPdfFileName(), 'pdf_file_name field is not empty');
        Assert::assertEmpty($this->email->getPdfString(), 'pdf_string field is not empty');
        Assert::assertEmpty($this->email->getSubject(), 'subject field is not empty');
        Assert::assertEmpty($this->email->getEMailTo(), 'to field is not empty');
    }

    public function testMakeSubject()
    {
        $this->email->setPdfFileName("filename.pdf");
        $this->email->setFromFriendly("Richard Rowley");

        Assert::assertEquals("filename.pdf ready for automatic credit card payment",
            $this->email->makeSubject('invoice_eway'));

        Assert::assertEquals("filename.pdf secure credit card payment successful",
            $this->email->makeSubject('invoice_eway_receipt'));

        Assert::assertEquals("filename.pdf has been emailed",
            $this->email->makeSubject('invoice_receipt'));

        Assert::assertEquals("filename.pdf from Richard Rowley",
            $this->email->makeSubject('invoice'));
    }


    public function testSend()
    {
        $results = $this->email->send();
        Assert::assertEquals("One or more required fields is missing", $results['message']);

        $this->email->setBody("This is the message body");
        try {
            $this->email->setFormat("invoice");
        } catch (Exception $exp) {
            Assert::fail("Unexpected exception thrown by setFormat. Error: {$exp->getMessage()}");
        }
        $this->email->setFrom("from@gmail.com");
        $this->email->setSubject("Email subject");
        $this->email->setEmailTo("to@gmail.com");
        $this->email->setPdfString("PDF string");
        $results = $this->email->send();
        Assert::assertEquals("Both pdfString and pdfFileName must be set or left empty", $results['message']);

        // Note, no further testing of this method deemed necessary.
    }

    public function testSetGetBody()
    {
        $this->email->setBody("This is the body");
        Assert::assertEquals("This is the body", $this->email->getBody());
    }

    public function testSetGetFrom()
    {
        $result = $this->email->setFrom("me@gmail.com");
        Assert::assertTrue($result, "testSetGetFrom setFrom for valid email returned a false result");
        Assert::assertEquals("me@gmail.com", $this->email->getFrom());

        $result = $this->email->setFrom("invalid_email_address");
        Assert::assertFalse($result, "testSetGetFrom setFrom for invalid email returned a true result");
    }

    public function testSetGetFormat()
    {
        try {
            $this->email->setFormat("invoice");
            Assert::assertEquals("invoice", $this->email->getFormat());
        } catch (Exception $exp) {
            Assert::fail("testSetGetFormat setFormat for invoice throws exception {$exp->getMessage()}");
        }
        try {
            $this->email->setFormat("statement");
            Assert::assertEquals("statement", $this->email->getFormat());
        } catch (Exception $exp) {
            Assert::fail("testSetGetFormat setFormat for statement throws exception {$exp->getMessage()}");
        }
        try {
            $this->email->setFormat("cron");
            Assert::assertEquals("cron", $this->email->getFormat());
        } catch (Exception $exp) {
            Assert::fail("testSetGetFormat setFormat for cron throws exception {$exp->getMessage()}");
        }
        try {
            $this->email->setFormat("cron_invoice");
            Assert::assertEquals("cron_invoice", $this->email->getFormat());
        } catch (Exception $exp) {
            Assert::fail("testSetGetFormat setFormat for cron_invoice throws exception {$exp->getMessage()}");
        }

        self::expectException(Exception::class);
        $this->email->setFormat("");
    }

    public function testSetGetPdfFileName()
    {
        $this->email->setPdfFileName("my_filename.pdf");
        Assert::assertEquals("my_filename.pdf", $this->email->getPdfFileName());
    }

    public function testSetGetFromFriendly()
    {
        $this->email->setFromFriendly("Richard Rowley");
        Assert::assertEquals("Richard Rowley", $this->email->getFromFriendly());
    }

    public function testSetGetBcc()
    {
        $this->email->setBcc("me@gmail.com");
        Assert::assertEquals("me@gmail.com", $this->email->getBcc());

        Assert::assertFalse($this->email->setBcc("invalid_email_address"), "Expected failed setBcc() call.");
    }

    public function testSetEmailTo()
    {
        $this->email->setEmailTo("me@gmail.com;you@gmail.com");
        Assert::assertEquals("me@gmail.com;you@gmail.com", $this->email->getEmailTo());

        Assert::assertFalse($this->email->setEmailTo("invalid_email_address"), "Expected failed setEmailTo() call.");

    }
}
