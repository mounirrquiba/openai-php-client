<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\FileDelete;
use PHPUnit\Framework\TestCase;

final class FileDeleteTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testFileDeleteInstance(): void
    {
        $fileDelete = new FileDelete();

        $this->assertInstanceOf(FileDelete::class, $fileDelete);

        $this->assertInstanceOf(OpenAi::class, $fileDelete->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $fileDelete->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $fileDelete->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($fileDelete->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($fileDelete->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testFileDeleteClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $fileDelete = (new FileDelete('file-S3MdWDGdLUADe61uw6iwYzcs'))
            ->create();
    }

    public function testFileDeleteResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/fileDelete.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $fileDelete = (new FileDelete('file-DjEv26W691AwaOX4DZukmrwq'))
            ->create();

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/fileDelete.response.json', json_encode($fileDelete->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $fileDelete->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $fileDelete->getResponse());
    }
}
