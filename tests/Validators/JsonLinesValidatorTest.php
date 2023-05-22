<?php

declare(strict_types=1);

use MounirRquiba\OpenAi\Validators\JsonLinesValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use PHPUnit\Framework\TestCase;

class JsonLinesValidatorTest extends TestCase
{
    public function testJsonlValues(): void
    {
        $config = ['name' => 'file', RequiredValidator::NAME => true, 'type' => JsonLinesValidator::NAME];

        $jsonl = JsonLinesValidator::create([
            ['prompt' => '<prompt text>', 'completion' => '<ideal generated text>'],
            ['prompt' => '<prompt text>', 'completion' => '<ideal generated text>'],
            ['prompt' => '<prompt text>', 'completion' => '<ideal generated text>'],
        ]);

        $this->assertSame(JsonLinesValidator::isValid($config, $jsonl), true);
    }

    public function testInvalidJsonlValues(): void
    {
        $config = ['name' => 'file', RequiredValidator::NAME => true, 'type' => JsonLinesValidator::NAME];

        $jsonl = JsonLinesValidator::create([[]]);

        $this->assertSame(JsonLinesValidator::isValid($config, $jsonl), false);
        $this->assertSame(JsonLinesValidator::isValid($config, []), false);
        $this->assertSame(JsonLinesValidator::isValid($config, null), false);
        $this->assertSame(JsonLinesValidator::isValid($config, true), false);
        $this->assertSame(JsonLinesValidator::isValid($config, false), false);
        $this->assertSame(JsonLinesValidator::isValid($config, ''), false);
        $this->assertSame(JsonLinesValidator::isValid($config, 'false'), false);
    }
}
