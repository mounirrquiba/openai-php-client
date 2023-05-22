<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\Engine;
use PHPUnit\Framework\TestCase;

final class EngineTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testEngineInstance(): void
    {
        $engine = new Engine();

        $this->assertInstanceOf(Engine::class, $engine);

        $this->assertInstanceOf(OpenAi::class, $engine->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $engine->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $engine->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($engine->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($engine->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testEngineClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $engine = (new Engine('text-davinci-003'))->create();
    }

    public function testEngineResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/engine.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
           'handler' => HandlerStack::create(
               new MockHandler([ $response, $response ])
           ),
        ]);

        $engine = (new Engine('text-davinci-003'))->create();

        $this->openIa->getOpenAiRequest()->clearMockClient();

        $this->assertSame(json_decode($message, true), $engine->getResponse());
    }
}
