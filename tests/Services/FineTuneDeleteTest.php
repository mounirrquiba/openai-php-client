<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\FineTuneDelete;
use PHPUnit\Framework\TestCase;

final class FineTuneDeleteTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testFineTuneDeleteInstance(): void
    {
        $fineTuneDelete = new FineTuneDelete();

        $this->assertInstanceOf(FineTuneDelete::class, $fineTuneDelete);

        $this->assertInstanceOf(OpenAi::class, $fineTuneDelete->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $fineTuneDelete->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $fineTuneDelete->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($fineTuneDelete->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($fineTuneDelete->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testFineTuneDeleteClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $fineTuneDelete = (new FineTuneDelete('curie:ft-personal-2023-05-15-22-35-28'))
            ->create();
    }

    public function testFineTuneDeleteResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/fineTuneDelete.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $fineTuneDelete = (new FineTuneDelete('curie:ft-personal-2023-05-15-22-35-28'))
            ->create();

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/fineTuneDelete.response.json', json_encode($fineTuneDelete->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $fineTuneDelete->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $fineTuneDelete->getResponse());
    }
}
