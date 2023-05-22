<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\File;
use PHPUnit\Framework\TestCase;

final class FileTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testFileInstance(): void
    {
        $file = new File();

        $this->assertInstanceOf(File::class, $file);

        $this->assertInstanceOf(OpenAi::class, $file->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $file->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $file->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($file->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($file->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testFileClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $file = (new File('file-DILkImh8E8Gl3PEGY1kD95BA'))
            ->create();
    }

    public function testFileResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/file.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $file = (new File('file-DILkImh8E8Gl3PEGY1kD95BA'))
            ->create();

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/file.response.json', json_encode($file->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $file->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $file->getResponse());
    }
}
