<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\FineTuneCancel;
use PHPUnit\Framework\TestCase;

final class FineTuneCancelTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testFineTuneCancelInstance(): void
    {
        $fineTuneCancel = new FineTuneCancel();

        $this->assertInstanceOf(FineTuneCancel::class, $fineTuneCancel);

        $this->assertInstanceOf(OpenAi::class, $fineTuneCancel->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $fineTuneCancel->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $fineTuneCancel->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($fineTuneCancel->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($fineTuneCancel->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testFineTuneCancelClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $fineTuneCancel = (new FineTuneCancel('ft-o68Br21m9VqSzbbHYcq9TE2z'))
            ->create();
    }

    public function testFineTuneCancelResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/fineTuneCancel.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $fineTuneCancel = (new FineTuneCancel('ft-o68Br21m9VqSzbbHYcq9TE2z'))
            ->create();

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/fineTuneCancel.response.json', json_encode($fineTuneCancel->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $fineTuneCancel->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $fineTuneCancel->getResponse());
    }
}
