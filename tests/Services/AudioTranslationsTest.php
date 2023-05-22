<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\Exceptions\FileNotFoundException;
use MounirRquiba\OpenAi\Exceptions\InvalidParameterException;
use MounirRquiba\OpenAi\Exceptions\RequiredParameterException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\AudioTranslations;
use PHPUnit\Framework\TestCase;

final class AudioTranslationsTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testAudioTranslationsInstance(): void
    {
        $audioTranslations = new AudioTranslations();

        $this->assertInstanceOf(AudioTranslations::class, $audioTranslations);

        $this->assertInstanceOf(OpenAi::class, $audioTranslations->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $audioTranslations->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $audioTranslations->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($audioTranslations->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($audioTranslations->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testAudioTranslationsClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $audioTranslations = (new AudioTranslations())->create([
            'file' => __DIR__ . '/Fixtures/input/multilingual.mp3',
            'model' => 'whisper-1',
            'response_format' => 'json',
        ]);
    }

    public function testAudioTranslationsFileRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $audioTranslations = (new AudioTranslations())->create([
            'model' => 'whisper-1',
        ]);
    }

    public function testAudioTranslationsModelRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $audioTranslations = (new AudioTranslations())->create([
            'file' => __DIR__ . '/Fixtures/input/multilingual.mp3',
        ]);
    }

    public function testAudioTranslationsModelInvalidParameterException(): void
    {
        $this->expectException(InvalidParameterException::class);

        $audioTranslations = (new AudioTranslations())->create([
            'file' => __DIR__ . '/Fixtures/input/multilingual.mp3',
            'model' => 'whisper-2',
        ]);
    }

    public function testAudioTranslationsFileNotFoundException(): void
    {
        $this->expectException(FileNotFoundException::class);

        $audioTranslations = (new AudioTranslations())->create([
            'file' => __DIR__ . '/Fixtures/input/multilingual.mp',
            'model' => 'whisper-1',
        ]);
    }

    public function testAudioTranslationsResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/audioTranslations.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $audioTranslations = (new AudioTranslations())->create([
            'file' => __DIR__ . '/Fixtures/input/multilingual.mp3',
            'model' => 'whisper-1',
            'response_format' => 'json',
        ]);

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/audioTranslations.response.json', json_encode($audioTranslations->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $audioTranslations->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $audioTranslations->getResponse());
    }
}
