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
use MounirRquiba\OpenAi\Services\AudioTranscriptions;
use PHPUnit\Framework\TestCase;

final class AudioTranscriptionsTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testAudioTranscriptionsInstance(): void
    {
        $audioTranscriptions = new AudioTranscriptions();

        $this->assertInstanceOf(AudioTranscriptions::class, $audioTranscriptions);

        $this->assertInstanceOf(OpenAi::class, $audioTranscriptions->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $audioTranscriptions->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $audioTranscriptions->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($audioTranscriptions->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($audioTranscriptions->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testAudioTranscriptionsClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $audioTranscriptions = (new AudioTranscriptions())->create([
            'file' => __DIR__ . '/Fixtures/input/multilingual.mp3',
            'model' => 'whisper-1',
            'response_format' => 'json',
        ]);
    }

    public function testAudioTranscriptionsFileRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $audioTranslations = (new AudioTranscriptions())->create([
            'model' => 'whisper-1',
        ]);
    }

    public function testAudioTranscriptionsModelRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $audioTranslations = (new AudioTranscriptions())->create([
            'file' => __DIR__ . '/Fixtures/input/multilingual.mp3',
        ]);
    }

    public function testAudioTranscriptionsModelInvalidParameterException(): void
    {
        $this->expectException(InvalidParameterException::class);

        $audioTranslations = (new AudioTranscriptions())->create([
            'file' => __DIR__ . '/Fixtures/input/multilingual.mp3',
            'model' => 'whisper-2',
        ]);
    }

    public function testAudioTranscriptionsFileNotFoundException(): void
    {
        $this->expectException(FileNotFoundException::class);

        $audioTranslations = (new AudioTranscriptions())->create([
            'file' => __DIR__ . '/Fixtures/input/multilingual.mp',
            'model' => 'whisper-1',
        ]);
    }

    public function testAudioTranscriptionsResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/audioTranscriptions.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $audioTranscriptions = (new AudioTranscriptions())->create([
            'file' => __DIR__ . '/Fixtures/input/multilingual.mp3',
            'model' => 'whisper-1',
            'response_format' => 'json',
        ]);

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/audioTranscriptions.response.json', json_encode($audioTranscriptions->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $audioTranscriptions->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $audioTranscriptions->getResponse());
    }
}
