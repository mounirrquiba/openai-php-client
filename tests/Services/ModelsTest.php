<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\Models;
use PHPUnit\Framework\TestCase;

final class ModelsTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testModelsInstance(): void
    {
        $models = new Models();

        $this->assertInstanceOf(Models::class, $models);

        $this->assertInstanceOf(OpenAi::class, $models->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $models->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $models->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($models->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($models->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testModelsClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $models = (new Models())->create();
    }

    public function testModelsResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/models.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
           'handler' => HandlerStack::create(
               new MockHandler([ $response, $response ])
           ),
        ]);

        $models = (new Models())->create();

        $this->openIa->getOpenAiRequest()->clearMockClient();

        $this->assertSame(json_decode($message, true), $models->getResponse());
    }
}
