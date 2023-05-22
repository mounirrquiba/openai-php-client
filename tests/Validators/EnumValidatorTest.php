<?php

declare(strict_types=1);

use MounirRquiba\OpenAi\Validators\EnumValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use PHPUnit\Framework\TestCase;

class EnumValidatorTest extends TestCase
{
    public function testEnumValues(): void
    {
        $config = ['name' => 'size', RequiredValidator::NAME => false, 'type' => EnumValidator::NAME, 'choices' => ['256x256', '512x512', '1024x1024']];

        $this->assertSame(EnumValidator::isValid($config, '256x256'), true);
        $this->assertSame(EnumValidator::isValid($config, '512x512'), true);
        $this->assertSame(EnumValidator::isValid($config, '1024x1024'), true);
    }

    public function testInvalidEnumValues(): void
    {
        $config = ['name' => 'size', RequiredValidator::NAME => false, 'type' => EnumValidator::NAME, 'choices' => ['256x256', '512x512', '1024x1024']];

        $this->assertSame(EnumValidator::isValid($config, ''), false);
        $this->assertSame(EnumValidator::isValid($config, '512x51'), false);
        $this->assertSame(EnumValidator::isValid($config, null), false);
        $this->assertSame(EnumValidator::isValid($config, []), false);
        $this->assertSame(EnumValidator::isValid($config, true), false);
        $this->assertSame(EnumValidator::isValid($config, false), false);
    }
}
