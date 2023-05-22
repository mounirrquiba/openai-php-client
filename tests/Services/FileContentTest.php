<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\FileContent;
use PHPUnit\Framework\TestCase;

final class FileContentTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testFileContentInstance(): void
    {
        $filecontent = new FileContent();

        $this->assertInstanceOf(FileContent::class, $filecontent);

        $this->assertInstanceOf(OpenAi::class, $filecontent->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $filecontent->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $filecontent->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($filecontent->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($filecontent->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    // public function testFileContentClientExceptionRequest(): void
    // {
    // 	$this->expectException(BadResponseException::class);

    // 	$filecontent = (new FileContent('file-DILkImh8E8Gl3PEGY1kD95BA'))
    // 	    ->create();
    // }

    public function testFileContentResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/filecontent.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $filecontent = (new FileContent('file-DILkImh8E8Gl3PEGY1kD95BA'))
            ->create();

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/filecontent.response.json', $filecontent->getResponse());
        // var_dump('========================', $filecontent->getResponse(), '========================');

        $this->assertSame($message, $filecontent->getResponse());
    }
}
