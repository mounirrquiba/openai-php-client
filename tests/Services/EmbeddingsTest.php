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
use MounirRquiba\OpenAi\Services\Embeddings;
use PHPUnit\Framework\TestCase;

final class EmbeddingsTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testEmbeddingsInstance(): void
    {
        $embeddings = new Embeddings();

        $this->assertInstanceOf(Embeddings::class, $embeddings);

        $this->assertInstanceOf(OpenAi::class, $embeddings->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $embeddings->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $embeddings->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($embeddings->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($embeddings->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testEmbeddingsClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $embeddings = (new Embeddings())->create([
            'model' => 'text-embedding-ada-002',
            'input' => "Le pain etait bon et le boulanger...",
        ]);
    }

    public function testEmbeddingsModelRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $embeddings = (new Embeddings())->create([
            'input' => "Le pain etait bon et le boulanger...",
        ]);
    }

    public function testEmbeddingsInputRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $embeddings = (new Embeddings())->create([
            'model' => 'text-embedding-ada-002',
        ]);
    }

    public function testEmbeddingsResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/embeddings.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $embeddings = (new Embeddings())->create([
            'model' => 'text-embedding-ada-002',
            'input' => "Le pain etait bon et le boulanger...",
        ]);

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/embeddings.response.json', json_encode($embeddings->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $embeddings->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $embeddings->getResponse());
    }
}
