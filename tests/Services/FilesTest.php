<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\Files;
use PHPUnit\Framework\TestCase;

final class FilesTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testFilesInstance(): void
    {
        $file = new Files();

        $this->assertInstanceOf(Files::class, $file);

        $this->assertInstanceOf(OpenAi::class, $file->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $file->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $file->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($file->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($file->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testFilesClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $files = (new Files())
            ->create();
    }

    public function testFilesResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/files.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $files = (new Files())
            ->create();

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/files.response.json', json_encode($files->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $files->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $files->getResponse());
    }
}
