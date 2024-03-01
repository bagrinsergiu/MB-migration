<?php
// This is the acknowledged namespace of your class, please update it as per your project.
namespace MBMigration\Builder\Layout;

use PHPUnit\Framework\TestCase;

class LayoutUtilsTest extends TestCase
{
    private $layoutUtil;

    protected function setUp(): void
    {
        $this->layoutUtil = new LayoutUtils();
    }

    public function testCheckPhoneNumber3()
    {

        $this->assertTrue($this->layoutUtil->checkPhoneNumber('1234567890'));
        $this->assertTrue($this->layoutUtil->checkPhoneNumber('360-720-0962'));
        $this->assertTrue($this->layoutUtil->checkPhoneNumber('8 (123) 456-7890'));
        $this->assertTrue($this->layoutUtil->checkPhoneNumber('+8 (123) 456-7890'));

        $this->assertFalse($this->layoutUtil->checkPhoneNumber('abcdefghij'));
        $this->assertFalse($this->layoutUtil->checkPhoneNumber('abcdefghij 123312'));
    }


    /**
     * @dataProvider getPhoneNumberVariations
     *
     * @param $number
     * @param $valid
     * @return void
     */
    public function testCheckPhoneNumber($number, $valid): void
    {
        $this->assertEquals(
            $valid,
            $this->layoutUtil->checkPhoneNumber($number),
            'The phone number must be '.($valid ? 'valid' : 'invalid')
        );
    }

    public function getPhoneNumberVariations()
    {
        return [
            ['1234567890', true],
            ['360-720-0962', true],
            ['8 (123) 456-7890', true],
            ['+8 (123) 456-7890', true],
            ['abcdefghij', false],
            ['abcdefghij 123312', false]
        ];
    }
}