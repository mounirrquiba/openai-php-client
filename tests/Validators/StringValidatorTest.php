<?php

declare(strict_types=1);

use MounirRquiba\OpenAi\Validators\RequiredValidator;
use MounirRquiba\OpenAi\Validators\StringValidator;
use PHPUnit\Framework\TestCase;

class StringValidatorTest extends TestCase
{
    public function testStringValues(): void
    {
        $config = ['name' => 'prompt', RequiredValidator::NAME => true, 'type' => StringValidator::NAME, 'max' => 10, 'min' => 1];

        $this->assertSame(StringValidator::isValid($config, 'Hello Moon'), true);
        $this->assertSame(StringValidator::isValid($config, ''), true);
    }

    public function testInvalidStringValues(): void
    {
        $config = ['name' => 'prompt', RequiredValidator::NAME => true, 'type' => StringValidator::NAME, 'max' => 10, 'min' => 1];

        $this->assertSame(StringValidator::isValid($config, 'Hello Moondqsdq qs dqs dqs qs qsd qsd'), false);
        $this->assertSame(StringValidator::isValid($config, []), false);
        $this->assertSame(StringValidator::isValid($config, ['true']), false);
        $this->assertSame(StringValidator::isValid($config, null), false);
        $this->assertSame(StringValidator::isValid($config, 10), false);
        $this->assertSame(StringValidator::isValid($config, 0), false);
        $this->assertSame(StringValidator::isValid($config, true), false);
        $this->assertSame(StringValidator::isValid($config, false), false);
    }
}
