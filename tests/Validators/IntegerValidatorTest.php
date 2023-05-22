<?php

declare(strict_types=1);

use MounirRquiba\OpenAi\Validators\IntegerValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use PHPUnit\Framework\TestCase;

class IntegerValidatorTest extends TestCase
{
    public function testIntegerValues(): void
    {
        $config = ['name' => 'n', RequiredValidator::NAME => false, 'type' => IntegerValidator::NAME, 'max' => 10, 'min' => 1];

        $this->assertSame(IntegerValidator::isValid($config, 1), true);
        $this->assertSame(IntegerValidator::isValid($config, 10), true);
    }

    public function testInvalidIntegerValues(): void
    {
        $config = ['name' => 'n', RequiredValidator::NAME => false, 'type' => IntegerValidator::NAME, 'max' => 10, 'min' => 1];

        $this->assertSame(IntegerValidator::isValid($config, 11), false);
        $this->assertSame(IntegerValidator::isValid($config, []), false);
        $this->assertSame(IntegerValidator::isValid($config, '1212'), false);
    }
}
