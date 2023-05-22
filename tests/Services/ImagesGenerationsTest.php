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
use MounirRquiba\OpenAi\Services\ImagesGenerations;
use PHPUnit\Framework\TestCase;

final class ImagesGenerationsTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testImagesGenerationsInstance(): void
    {
        $imagesGenerations = new ImagesGenerations();

        $this->assertInstanceOf(ImagesGenerations::class, $imagesGenerations);

        $this->assertInstanceOf(OpenAi::class, $imagesGenerations->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $imagesGenerations->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $imagesGenerations->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($imagesGenerations->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($imagesGenerations->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testImagesGenerationsClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $imagesGenerations = (new ImagesGenerations())->create([
            'prompt' => 'une lune en PHP',
            'n' => 2,
        ]);
    }

    public function testImagesGenerationsRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $imagesGenerations = (new ImagesGenerations())->create([
            'n' => 2,
        ]);
    }

    public function testImagesGenerationsModelRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $imagesGenerations = (new ImagesGenerations())->create([
            'prompt' => [],
            'n' => 2,
        ]);
    }

    public function testImagesGenerationsResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/imagesGenerations.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $imagesGenerations = (new ImagesGenerations())->create([
            'prompt' => 'une lune en PHP',
            'n' => 3,
        ]);

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/imagesGenerations.response.json', json_encode($imagesGenerations->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $imagesGenerations->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $imagesGenerations->getResponse());
    }
}
