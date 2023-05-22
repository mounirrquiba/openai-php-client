<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\Exceptions\RequiredParameterException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\FineTuneCreate;
use PHPUnit\Framework\TestCase;

final class FineTuneCreateTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testFineTuneCreateInstance(): void
    {
        $fineTuneCreate = new FineTuneCreate();

        $this->assertInstanceOf(FineTuneCreate::class, $fineTuneCreate);

        $this->assertInstanceOf(OpenAi::class, $fineTuneCreate->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $fineTuneCreate->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $fineTuneCreate->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($fineTuneCreate->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($fineTuneCreate->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testFineTuneCreateClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $fineTuneCreate = (new FineTuneCreate())
            ->create([
                'training_file' => 'file-rr52uDaNMcspoOZ4bAu3wbOS',
            ]);
    }

    public function testFineTuneCreateTrainingFileRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $fineTuneCreate = (new FineTuneCreate())
            ->create([
            ]);
    }

    public function testFineTuneCreateResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/fineTuneCreate.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $fineTuneCreate = (new FineTuneCreate())
            ->create([
                'training_file' => 'file-rr52uDaNMcspoOZ4bAu3wbOS',
            ]);

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/fineTuneCreate.response.json', json_encode($fineTuneCreate->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $fineTuneCreate->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $fineTuneCreate->getResponse());
    }
}
