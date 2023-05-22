<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\Model;
use PHPUnit\Framework\TestCase;

final class ModelTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testModelInstance(): void
    {
        $model = new Model('text-davinci-003');

        $this->assertInstanceOf(Model::class, $model);

        $this->assertInstanceOf(OpenAi::class, $model->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $model->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $model->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($model->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($model->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testModelClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $model = (new Model('text-davinci-003'))->create();
    }

    public function testModelResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/model.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
           'handler' => HandlerStack::create(
               new MockHandler([ $response, $response ])
           ),
        ]);

        $model = (new Model('text-davinci-003'))->create();

        $this->openIa->getOpenAiRequest()->clearMockClient();

        $this->assertSame(json_decode($message, true), $model->getResponse());
    }
}
