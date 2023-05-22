<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\Exceptions\FileNotFoundException;
use MounirRquiba\OpenAi\Exceptions\RequiredParameterException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\ImagesEdits;
use PHPUnit\Framework\TestCase;

final class ImagesEditsTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testImagesEditsInstance(): void
    {
        $imagesEdits = new ImagesEdits();

        $this->assertInstanceOf(ImagesEdits::class, $imagesEdits);

        $this->assertInstanceOf(OpenAi::class, $imagesEdits->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $imagesEdits->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $imagesEdits->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($imagesEdits->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($imagesEdits->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testImagesEditsClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $imagesEdits = (new ImagesEdits())->create([
            'image' => __DIR__ . '/Fixtures/input/image_edit_original.png',
            'mask' => __DIR__ . '/Fixtures/input/image_edit_mask.png',
            'prompt' => 'A cute baby sea otter wearing a beret',
            'n' => 2,
        ]);
    }

    public function testImagesEditsRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $imagesEdits = (new ImagesEdits())->create([
            'mask' => __DIR__ . '/Fixtures/input/image_edit_mask.png',
            'prompt' => 'A cute baby sea otter wearing a beret',
            'n' => 2,
        ]);
    }

    public function testImagesEditsFileNotFoundException(): void
    {
        $this->expectException(FileNotFoundException::class);

        $imagesEdits = (new ImagesEdits())->create([
            'image' => __DIR__ . '/Fixtures/input/image_edit_original.pn',
            'mask' => __DIR__ . '/Fixtures/input/image_edit_mask.png',
            'prompt' => 'A cute baby sea otter wearing a beret',
            'n' => 2,
        ]);
    }

    public function testImagesEditsResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/imagesEdits.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $imagesEdits = (new ImagesEdits())->create([
            'image' => __DIR__ . '/Fixtures/input/image_edit_original.png',
            'mask' => __DIR__ . '/Fixtures/input/image_edit_mask.png',
            'prompt' => 'A cute baby sea otter wearing a beret',
            'n' => 2,
        ]);

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/imagesEdits.response.json', json_encode($imagesEdits->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $imagesEdits->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $imagesEdits->getResponse());
    }
}
