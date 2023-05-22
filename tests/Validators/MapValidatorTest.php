<?php

declare(strict_types=1);

use MounirRquiba\OpenAi\Validators\MapValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use PHPUnit\Framework\TestCase;

class MapValidatorTest extends TestCase
{
    public function testMapValues(): void
    {
        $config = ['name' => 'logit_bias', RequiredValidator::NAME => false, 'type' => MapValidator::NAME, 'default' => null];

        $this->assertSame(MapValidator::isValid($config, null), true);

        $logitBias = json_encode([50256 => -100]);
        $this->assertSame(MapValidator::isValid($config, $logitBias), true);
    }

    public function testInvalidMapValues(): void
    {
        $config = ['name' => 'logit_bias', RequiredValidator::NAME => false, 'type' => MapValidator::NAME, 'default' => null];

        $this->assertSame(MapValidator::isValid($config, 'Hello'), false);
        $this->assertSame(MapValidator::isValid($config, true), false);
        $this->assertSame(MapValidator::isValid($config, false), false);
        $this->assertSame(MapValidator::isValid($config, []), false);
    }
}
