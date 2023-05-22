<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\Exceptions\InvalidParameterException;
use MounirRquiba\OpenAi\Exceptions\RequiredParameterException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\FileUpload;
use PHPUnit\Framework\TestCase;

final class FileUploadTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testFileUploadInstance(): void
    {
        $fileUpload = new FileUpload();

        $this->assertInstanceOf(FileUpload::class, $fileUpload);

        $this->assertInstanceOf(OpenAi::class, $fileUpload->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $fileUpload->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $fileUpload->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($fileUpload->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($fileUpload->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testFileUploadClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $fileUpload = (new FileUpload())
            ->create([
            'file' => __DIR__ . '/Fixtures/input/FineTuningSample1.jsonl',
            'purpose' => 'fine-tune',
        ]);
    }

    public function testFileUploadBadFileNameInvalidParameterException(): void
    {
        $this->expectException(InvalidParameterException::class);

        $fileUpload = (new FileUpload())
            ->create([
                'file' => __DIR__ . '/Fixtures/input/FineTuningSample1.json',
                'purpose' => 'fine-tune',
            ]);
    }

    public function testFileUploadPurposeRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $fileUpload = (new FileUpload())
            ->create([
                'file' => __DIR__ . '/Fixtures/input/FineTuningSample1.jsonl',
            ]);
    }

    public function testFileUploadFileRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $fileUpload = (new FileUpload())
            ->create([
            'purpose' => 'fine-tune',
        ]);
    }

    public function testFileUploadResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/fileUpload.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $fileUpload = (new FileUpload())
            ->create([
                'file' => __DIR__ . '/Fixtures/input/FineTuningSample1.jsonl',
                'purpose' => 'fine-tune',
            ]);

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/fileUpload.response.json', json_encode($fileUpload->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $fileUpload->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $fileUpload->getResponse());
    }
}
