<?php
// This is the acknowledged namespace of your class, please update it as per your project.
namespace MBIgration\Builder\Layout;

use MBMigration\Builder\Layout\LayoutUtils;
use PHPUnit\Framework\TestCase;

class LayoutUtilsTest extends TestCase
{
    private $layoutUtil;

    protected function setUp(): void
    {
        $this->layoutUtil = new LayoutUtils();
    }

    public function testCheckPhoneNumber()
    {

        $this->assertTrue($this->layoutUtil->checkPhoneNumber('1234567890'));
        $this->assertTrue($this->layoutUtil->checkPhoneNumber('360-720-0962'));
        $this->assertTrue($this->layoutUtil->checkPhoneNumber('8 (123) 456-7890'));
        $this->assertTrue($this->layoutUtil->checkPhoneNumber('+8 (123) 456-7890'));

        $this->assertFalse($this->layoutUtil->checkPhoneNumber('abcdefghij'));
        $this->assertFalse($this->layoutUtil->checkPhoneNumber('abcdefghij 123312'));
    }
}