<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\Exceptions\InvalidParameterException;
use MounirRquiba\OpenAi\Exceptions\RequiredParameterException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\Chat;
use PHPUnit\Framework\TestCase;

final class ChatTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testChatInstance(): void
    {
        $chat = new Chat();

        $this->assertInstanceOf(Chat::class, $chat);

        $this->assertInstanceOf(OpenAi::class, $chat->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $chat->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $chat->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($chat->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($chat->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testChatClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $chat = (new Chat())->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => "Je ne comprends pas le PHP tu peux m'aider ?"],
            ],
        ]);
    }

    public function testChatModelRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $chat = (new Chat())->create([
            'messages' => [
                ['role' => 'user', 'content' => "Je ne comprends pas le PHP tu peux m'aider ?"],
            ],
        ]);
    }

    public function testChatMessagesRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $chat = (new Chat())->create([
            'model' => 'gpt-3.5-turbo',
        ]);
    }

    public function testChatMessagesRoleInvalidParameterException(): void
    {
        $this->expectException(InvalidParameterException::class);

        $chat = (new Chat())->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['content' => "Je ne comprends pas le PHP tu peux m'aider ?"],
            ],
        ]);
    }

    public function testChatMessagesContentsInvalidParameterException(): void
    {
        $this->expectException(InvalidParameterException::class);

        $chat = (new Chat())->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user'],
            ],
        ]);
    }

    public function testChatMessagesRoleValueInvalidParameterException(): void
    {
        $this->expectException(InvalidParameterException::class);

        $chat = (new Chat())->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'other', 'content' => "Je ne comprends pas le PHP tu peux m'aider ?"],
            ],
        ]);
    }

    public function testChatResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/chat.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $chat = (new Chat())->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => "Je ne comprends pas le PHP tu peux m'aider ?"],
            ],
        ]);

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/chat.response.json', json_encode($chat->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $chat->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $chat->getResponse());
    }
}
