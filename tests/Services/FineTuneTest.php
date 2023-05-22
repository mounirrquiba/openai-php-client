<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\FineTune;
use PHPUnit\Framework\TestCase;

final class FineTuneTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testFineTuneInstance(): void
    {
        $fineTune = new FineTune();

        $this->assertInstanceOf(FineTune::class, $fineTune);

        $this->assertInstanceOf(OpenAi::class, $fineTune->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $fineTune->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $fineTune->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($fineTune->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($fineTune->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testFineTuneClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $fineTune = (new FineTune('ft-kXSupnLOlDZYomVs6yfhsbgg'))
            ->create();
    }

    public function testFineTuneResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/fineTune.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $fineTune = (new FineTune('ft-kXSupnLOlDZYomVs6yfhsbgg'))
            ->create();

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/fineTune.response.json', json_encode($fineTune->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $fineTune->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $fineTune->getResponse());
    }
}
