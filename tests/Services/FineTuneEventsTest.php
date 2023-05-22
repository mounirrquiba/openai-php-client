<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\FineTuneEvents;
use PHPUnit\Framework\TestCase;

final class FineTuneEventsTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testFineTuneEventsInstance(): void
    {
        $fineTuneEvents = new FineTuneEvents();

        $this->assertInstanceOf(FineTuneEvents::class, $fineTuneEvents);

        $this->assertInstanceOf(OpenAi::class, $fineTuneEvents->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $fineTuneEvents->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $fineTuneEvents->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($fineTuneEvents->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($fineTuneEvents->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testFineTuneEventsClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $fineTuneEvents = (new FineTuneEvents('ft-kXSupnLOlDZYomVs6yfhsbgg'))
            ->create();
    }

    public function testFineTuneEventsResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/fineTuneEvents.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $fineTuneEvents = (new FineTuneEvents('ft-kXSupnLOlDZYomVs6yfhsbgg'))
            ->create();

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/fineTuneEvents.response.json', json_encode($fineTuneEvents->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $fineTuneEvents->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $fineTuneEvents->getResponse());
    }
}
