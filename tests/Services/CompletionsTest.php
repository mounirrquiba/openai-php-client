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
use MounirRquiba\OpenAi\Services\Completions;
use PHPUnit\Framework\TestCase;

final class CompletionsTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testCompletionsInstance(): void
    {
        $completions = new Completions();

        $this->assertInstanceOf(Completions::class, $completions);

        $this->assertInstanceOf(OpenAi::class, $completions->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $completions->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $completions->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($completions->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($completions->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testCompletionsClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $completions = (new Completions())->create([
            'model' => "text-davinci-003",
            'prompt' => "A table summarizing the fruits from Goocrux:\n\nThere are many fruits that were found on the recently discovered planet Goocrux. There are neoskizzles that grow there, which are purple and taste like candy. There are also loheckles, which are a grayish blue fruit and are very tart, a little bit like a lemon. Pounits are a bright green color and are more savory than sweet. There are also plenty of loopnovas which are a neon pink flavor and taste like cotton candy. Finally, there are fruits called glowls, which have a very sour and bitter taste which is acidic and caustic, and a pale orange tinge to them.\n\n| Fruit | Color | Flavor |",
            'temperature' => 1,
            'max_tokens' => 2048,
            'top_p' => 1,
            'best_of' => 20,
            'frequency_penalty' => 2,
            'presence_penalty' => 2,
        ]);
    }

    public function testCompletionsModelRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $completions = (new Completions())->create([
            'prompt' => "A table summarizing the fruits from Goocrux:\n\nThere are many fruits that were found on the recently discovered planet Goocrux. There are neoskizzles that grow there, which are purple and taste like candy. There are also loheckles, which are a grayish blue fruit and are very tart, a little bit like a lemon. Pounits are a bright green color and are more savory than sweet. There are also plenty of loopnovas which are a neon pink flavor and taste like cotton candy. Finally, there are fruits called glowls, which have a very sour and bitter taste which is acidic and caustic, and a pale orange tinge to them.\n\n| Fruit | Color | Flavor |",
            'temperature' => 1,
            'max_tokens' => 2048,
            'top_p' => 1,
            'best_of' => 20,
            'frequency_penalty' => 2,
            'presence_penalty' => 2,
        ]);
    }

    public function testCompletionsResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/completions.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $completions = (new Completions())->create([
            'model' => "text-davinci-003",
            'prompt' => "A table summarizing the fruits from Goocrux:\n\nThere are many fruits that were found on the recently discovered planet Goocrux. There are neoskizzles that grow there, which are purple and taste like candy. There are also loheckles, which are a grayish blue fruit and are very tart, a little bit like a lemon. Pounits are a bright green color and are more savory than sweet. There are also plenty of loopnovas which are a neon pink flavor and taste like cotton candy. Finally, there are fruits called glowls, which have a very sour and bitter taste which is acidic and caustic, and a pale orange tinge to them.\n\n| Fruit | Color | Flavor |",
            'temperature' => 1,
            'max_tokens' => 2048,
            'top_p' => 1,
            'best_of' => 20,
            'frequency_penalty' => 2,
            'presence_penalty' => 2,
        ]);

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/completions.response.json', json_encode($completions->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $completions->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $completions->getResponse());
    }
}
