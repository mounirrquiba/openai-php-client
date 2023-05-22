<?php

declare(strict_types=1);

use MounirRquiba\OpenAi\Validators\IntegerValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use PHPUnit\Framework\TestCase;

class RequiredValidatorTest extends TestCase
{
    public function testIsValidRequiredParam(): void
    {
        $config = ['name' => 'n', RequiredValidator::NAME => true, 'type' => IntegerValidator::NAME, 'max' => 10, 'min' => 1];

        $this->assertSame(RequiredValidator::isValid($config, 'ez'), true);

        $config['required'] = false;
        $this->assertSame(RequiredValidator::isValid($config, ''), true);
    }

    public function testIsInvalidRequiredParam(): void
    {
        $config = ['name' => 'n', RequiredValidator::NAME => true, 'type' => IntegerValidator::NAME, 'max' => 10, 'min' => 1];

        $this->assertSame(RequiredValidator::isValid($config, null), false);
        $this->assertSame(RequiredValidator::isValid($config, 0), false);
        $this->assertSame(RequiredValidator::isValid($config, ''), false);
        $this->assertSame(RequiredValidator::isValid($config, []), false);
    }
}
