<?php

declare(strict_types=1);

use MounirRquiba\OpenAi\Validators\EnumValidator;
use MounirRquiba\OpenAi\Validators\MessagesValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use MounirRquiba\OpenAi\Validators\StringValidator;

use PHPUnit\Framework\TestCase;

class MessagesValidatorTest extends TestCase
{
    private $config;

    protected function setUp(): void
    {
        $this->config = [
            'name' => MessagesValidator::NAME, RequiredValidator::NAME => true, 'type' => MessagesValidator::NAME,
            'fields' => [
                [ 'name' => 'role', RequiredValidator::NAME => true, 'type' => EnumValidator::NAME, 'choices' => ['system', 'user', 'assistant']],
                [ 'name' => 'content', RequiredValidator::NAME => true, 'type' => StringValidator::NAME ],
                [ 'name' => 'name', RequiredValidator::NAME => false, 'type' => StringValidator::NAME ],
            ],
        ];
    }

    public function testMessagesValues(): void
    {
        $this->assertSame(MessagesValidator::isValid($this->config, [ ['role' => 'user', 'content' => 'Nice job!'] ]), true);
        $this->assertSame(MessagesValidator::isValid($this->config, [ ['role' => 'system', 'content' => 'Nice job!'] ]), true);
        $this->assertSame(MessagesValidator::isValid($this->config, [ ['role' => 'assistant', 'content' => 'Nice job!'] ]), true);
    }

    public function testInvalidMessagesValues(): void
    {
        $this->assertSame(MessagesValidator::isValid($this->config, true), false);
        $this->assertSame(MessagesValidator::isValid($this->config, false), false);
        $this->assertSame(MessagesValidator::isValid($this->config, null), false);
        $this->assertSame(MessagesValidator::isValid($this->config, []), false);
        $this->assertSame(MessagesValidator::isValid($this->config, [ ['role' => 'user'] ]), false);
        $this->assertSame(MessagesValidator::isValid($this->config, [ ['content' => 'user'] ]), false);
        $this->assertSame(MessagesValidator::isValid($this->config, [ ['role' => 'user', 'content' => ''] ]), false);
        $this->assertSame(MessagesValidator::isValid($this->config, [ ['role' => 'other', 'content' => 'Hello'] ]), false);
    }
}
