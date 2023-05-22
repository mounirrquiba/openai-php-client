<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\FineTunes;
use PHPUnit\Framework\TestCase;

final class FineTunesTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testFineTunesInstance(): void
    {
        $fineTunes = new FineTunes();

        $this->assertInstanceOf(FineTunes::class, $fineTunes);

        $this->assertInstanceOf(OpenAi::class, $fineTunes->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $fineTunes->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $fineTunes->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($fineTunes->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($fineTunes->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testFineTunesClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $fineTunes = (new FineTunes())
            ->create();
    }

    public function testFineTunesResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/fineTunes.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $fineTunes = (new FineTunes())
            ->create();

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/fineTunes.response.json', json_encode($fineTunes->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $fineTunes->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $fineTunes->getResponse());
    }
}
