<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\Engines;
use PHPUnit\Framework\TestCase;

final class EnginesTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testEnginesInstance(): void
    {
        $engines = new Engines();

        $this->assertInstanceOf(Engines::class, $engines);

        $this->assertInstanceOf(OpenAi::class, $engines->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $engines->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $engines->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($engines->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($engines->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testEnginesClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $engines = (new Engines())->create();
    }

    public function testEnginesResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/engines.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
           'handler' => HandlerStack::create(
               new MockHandler([ $response, $response ])
           ),
        ]);

        $engines = (new Engines())->create();

        $this->openIa->getOpenAiRequest()->clearMockClient();

        $this->assertSame(json_decode($message, true), $engines->getResponse());
    }
}
