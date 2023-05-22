<?php

declare(strict_types=1);

use MounirRquiba\OpenAi\Validators\NumberValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use PHPUnit\Framework\TestCase;

class NumberValidatorTest extends TestCase
{
    public function testNumberValues(): void
    {
        $config = ['name' => 'top_p', RequiredValidator::NAME => false, 'type' => NumberValidator::NAME, 'default' => 1];

        $this->assertSame(NumberValidator::isValid($config, 1), true);
        $this->assertSame(NumberValidator::isValid($config, -1), true);
        $this->assertSame(NumberValidator::isValid($config, 2.0), true);
        $this->assertSame(NumberValidator::isValid($config, -2.0), true);
    }

    public function testInvalidNumberValues(): void
    {
        $config = ['name' => 'top_p', RequiredValidator::NAME => false, 'type' => NumberValidator::NAME, 'default' => 1];

        $this->assertSame(NumberValidator::isValid($config, false), false);
        $this->assertSame(NumberValidator::isValid($config, true), false);
        $this->assertSame(NumberValidator::isValid($config, ''), false);
        $this->assertSame(NumberValidator::isValid($config, 'Hello'), false);
        $this->assertSame(NumberValidator::isValid($config, []), false);
        $this->assertSame(NumberValidator::isValid($config, ['hello']), false);
    }
}
