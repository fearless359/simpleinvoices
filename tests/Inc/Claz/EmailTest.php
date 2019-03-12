<?php
/**
 * @name EmailTest.php
 * @author Richard Rowley
 * @license GPL V3 or above
 * Created: 20190304
 */

namespace test\Inc\Claz;

use Inc\Claz\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    /**
     * @var Email
     */
    protected $email;
    
    public function setUp()
    {
        $this->email = new Email;
    }

    public function test__construct()
    {
        self::assertEmpty($this->email->getBcc(), 'bcc field is not empty');
        self::assertEmpty($this->email->getBody(), 'body field is not empty');
        self::assertEmpty($this->email->getFormat(), 'format field is not empty');
        self::assertEmpty($this->email->getFrom(), 'from field is not empty');
        self::assertEmpty($this->email->getFromFriendly(), 'from_friendly field is not empty');
        self::assertEmpty($this->email->getPdfFileName(), 'pdf_file_name field is not empty');
        self::assertEmpty($this->email->getPdfString(), 'pdf_string field is not empty');
        self::assertEmpty($this->email->getSubject(), 'subject field is not empty');
        self::assertEmpty($this->email->getTo(), 'to field is not empty');
    }

    public function testMakeSubject()
    {
        $this->email->setPdfFileName("filename.pdf");
        $this->email->setFromFriendly("Richard Rowley");

        $this->assertEquals("filename.pdf ready for automatic credit card payment",
            $this->email->makeSubject('invoice_eway'));

        $this->assertEquals("filename.pdf secure credit card payment successful",
            $this->email->makeSubject('invoice_eway_receipt'));

        $this->assertEquals("filename.pdf has been emailed",
            $this->email->makeSubject('invoice_receipt'));

        $this->assertEquals("filename.pdf from Richard Rowley",
            $this->email->makeSubject('invoice'));
    }


    public function testSend()
    {
        $results = $this->email->send();
        $this->assertEquals("One or more required fields is missing", $results['message']);

        $this->email->setBody("This is the message body");
        $this->email->setFormat("invoice");
        $this->email->setFrom("from@gmail.com");
        $this->email->setSubject("Email subject");
        $this->email->setTo("to@gmail.com");
        $this->email->setPdfString("PDF string");
        $results = $this->email->send();
        $this->assertEquals("Both pdf_string and pdf_file_name must be set or left empty", $results['message']);

        // Note, no further testing of this method deemed necessary.
    }

    public function testSetGetBody()
    {
        $this->email->setBody("This is the body");
        $this->assertEquals("This is the body", $this->email->getBody());
    }

    public function testSetGetFrom()
    {
        $this->email->setFrom("me@gmail.com");
        self::assertEquals("me@gmail.com", $this->email->getFrom());

        $this->expectExceptionMessage("Invalid 'from' email address specified");
        $this->email->setFrom("invalid_email_address");
    }

    public function testSetGetFormat()
    {
        $this->email->setFormat("invoice");
        self::assertEquals("invoice", $this->email->getFormat());
        $this->email->setFormat("statement");
        self::assertEquals("statement", $this->email->getFormat());
        $this->email->setFormat("cron");
        self::assertEquals("cron", $this->email->getFormat());
        $this->email->setFormat("cron_invoice");
        self::assertEquals("cron_invoice", $this->email->getFormat());

        $this->expectExceptionMessage("Invalid format. Must be 'invoice', 'statement', 'cron' or 'crong_invoice'");
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

        $this->expectExceptionMessage("Invalid 'bcc' email address specified");
        $this->email->setBcc("invalid_email_address");
    }

    public function testSetGetTo()
    {
        $this->email->setTo("me@gmail.com");
        self::assertEquals("me@gmail.com", $this->email->getTo());

        $this->expectExceptionMessage("Invalid 'to' email address specified");
        $this->email->setTo("invalid_email_address");

    }
}
