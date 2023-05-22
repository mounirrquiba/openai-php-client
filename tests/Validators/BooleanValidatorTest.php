<?php

declare(strict_types=1);

use MounirRquiba\OpenAi\Validators\BooleanValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use PHPUnit\Framework\TestCase;

class BooleanValidatorTest extends TestCase
{
    public function testBooleanValues(): void
    {
        $config = ['name' => 'stream', RequiredValidator::NAME => false, 'type' => BooleanValidator::NAME, 'default' => false];

        $this->assertSame(BooleanValidator::isValid($config, true), true);
        $this->assertSame(BooleanValidator::isValid($config, false), true);
    }

    public function testInvalidBooleanValues(): void
    {
        $config = ['name' => 'stream', RequiredValidator::NAME => false, 'type' => BooleanValidator::NAME, 'default' => false];

        $this->assertSame(BooleanValidator::isValid($config, 'true'), false);
        $this->assertSame(BooleanValidator::isValid($config, 'false'), false);
        $this->assertSame(BooleanValidator::isValid($config, []), false);
        $this->assertSame(BooleanValidator::isValid($config, null), false);
    }
}
