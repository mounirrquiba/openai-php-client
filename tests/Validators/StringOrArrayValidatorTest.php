<?php

declare(strict_types=1);

use MounirRquiba\OpenAi\Validators\RequiredValidator;
use MounirRquiba\OpenAi\Validators\StringOrArrayValidator;
use PHPUnit\Framework\TestCase;

class StringOrArrayValidatorTest extends TestCase
{
    public function testStringOrArrayValues(): void
    {
        $config = ['name' => 'messages', RequiredValidator::NAME => true, 'type' => StringOrArrayValidator::NAME];

        $this->assertSame(StringOrArrayValidator::isValid($config, 'Hello Moon'), true);
        $this->assertSame(StringOrArrayValidator::isValid($config, ''), true);
        $this->assertSame(StringOrArrayValidator::isValid($config, []), true);
    }

    public function testInvalidStringOrArrayValues(): void
    {
        $config = ['name' => 'messages', RequiredValidator::NAME => true, 'type' => StringOrArrayValidator::NAME];

        $this->assertSame(StringOrArrayValidator::isValid($config, null), false);
        $this->assertSame(StringOrArrayValidator::isValid($config, true), false);
        $this->assertSame(StringOrArrayValidator::isValid($config, false), false);
    }
}
