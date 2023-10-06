<?php

namespace Tests\Feature\Rules;

use App\Rules\PhoneNumberFormat;
use Tests\LaravelTestCase;

class PhoneNumberFormatTest extends LaravelTestCase
{
    public function setUp(): void
    {
        $this->rule = new PhoneNumberFormat;
    }

    /**
     * @dataProvider validPhoneNumberFormats
     */
    public function testValidPhoneNumberFormatsPass($number)
    {
        $this->assertTrue((bool) $this->rule->passes('test', $number));
    }

    /**
     * @dataProvider invalidPhoneNumberFormats
     */
    public function testInvalidPhoneNumberFormatsFail($number)
    {
        $this->assertFalse((bool) $this->rule->passes('test', $number));
    }

    public function validPhoneNumberFormats()
    {
        return [
            ['01234567890'],
            ['01234 567890'],
            ['07123456789'],
            ['07123 456789'],
            ['012-345-6789 12345'],
        ];
    }

    public function invalidPhoneNumberFormats()
    {
        return [
            ["one"],
        ];
    }
}
