<?php

declare(strict_types=1);

use MounirRquiba\OpenAi\Validators\ArrayValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use PHPUnit\Framework\TestCase;

class ArrayValidatorTest extends TestCase
{
    public function testArrayValues(): void
    {
        $config = ['name' => 'messages', RequiredValidator::NAME => true, 'type' => ArrayValidator::NAME];

        $this->assertSame(ArrayValidator::isValid($config, []), true);
        $this->assertSame(ArrayValidator::isValid($config, ['Hello']), true);
    }

    public function testInvalidArrayValues(): void
    {
        $config = ['name' => 'messages', RequiredValidator::NAME => true, 'type' => ArrayValidator::NAME];

        $this->assertSame(ArrayValidator::isValid($config, 'Hello Moon'), false);
        $this->assertSame(ArrayValidator::isValid($config, ''), false);
        $this->assertSame(ArrayValidator::isValid($config, true), false);
        $this->assertSame(ArrayValidator::isValid($config, false), false);
    }
}
