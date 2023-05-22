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
use MounirRquiba\OpenAi\Services\ImagesVariations;
use PHPUnit\Framework\TestCase;

final class ImagesVariationsTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testImagesVariationsInstance(): void
    {
        $imagesVariations = new ImagesVariations();

        $this->assertInstanceOf(ImagesVariations::class, $imagesVariations);

        $this->assertInstanceOf(OpenAi::class, $imagesVariations->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $imagesVariations->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $imagesVariations->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($imagesVariations->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($imagesVariations->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testImagesVariationsClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $imagesVariations = (new ImagesVariations())->create([
            'image' => __DIR__ . '/Fixtures/input/image_edit_original.png',
            'n' => 2,
        ]);
    }

    public function testImagesVariationsRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $imagesVariations = (new ImagesVariations())->create([
            'n' => 2,
        ]);
    }

    public function testImagesVariationsFileNotFoundException(): void
    {
        $this->expectException(FileNotFoundException::class);

        $imagesVariations = (new ImagesVariations())->create([
            'image' => __DIR__ . '/Fixtures/input/image_edit_original.pn',
            'n' => 2,
        ]);
    }

    public function testImagesVariationsResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/imagesVariations.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $imagesVariations = (new ImagesVariations())->create([
            'image' => __DIR__ . '/Fixtures/input/image_edit_original.png',
            'n' => 2,
        ]);

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/imagesVariations.response.json', json_encode($imagesVariations->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $imagesVariations->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $imagesVariations->getResponse());
    }
}
