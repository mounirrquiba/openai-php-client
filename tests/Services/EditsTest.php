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
use MounirRquiba\OpenAi\Services\Edits;
use PHPUnit\Framework\TestCase;

final class EditsTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testEditsInstance(): void
    {
        $edits = new Edits();

        $this->assertInstanceOf(Edits::class, $edits);

        $this->assertInstanceOf(OpenAi::class, $edits->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $edits->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $edits->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($edits->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($edits->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testEditsClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $edits = (new Edits())->create([
            'model' => "text-davinci-edit-001",
            'input' => "Coment va le marché français de l'or ?\n\nDans quels pays y a-t-il le plus d'or ?\n",
            'instruction' => "corriger les fautes d'orthographe\nDonner la liste des 10 pays ou il y a le plus d'or dans l'ordre décroissant",
            'temperature' => 0.7,
            'top_p' => 1,
        ]);
    }

    public function testEditsModelRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $edits = (new Edits())->create([
            'input' => "Coment va le marché français de l'or ?\n\nDans quels pays y a-t-il le plus d'or ?\n",
            'instruction' => "corriger les fautes d'orthographe\nDonner la liste des 10 pays ou il y a le plus d'or dans l'ordre décroissant",
            'temperature' => 0.7,
            'top_p' => 1,
        ]);
    }

    public function testEditsInstructionRequiredParameterException(): void
    {
        $this->expectException(RequiredParameterException::class);

        $edits = (new Edits())->create([
            'model' => "text-davinci-edit-001",
            'input' => "Coment va le marché français de l'or ?\n\nDans quels pays y a-t-il le plus d'or ?\n",
            'temperature' => 0.7,
            'top_p' => 1,
        ]);
    }

    public function testEditsResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/edits.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $edits = (new Edits())->create([
            'model' => "text-davinci-edit-001",
            'input' => "Coment va le marché français de l'or ?\n\nDans quels pays y a-t-il le plus d'or ?\n",
            'instruction' => "corriger les fautes d'orthographe\nDonner la liste des 10 pays ou il y a le plus d'or dans l'ordre décroissant",
            'temperature' => 0.7,
            'top_p' => 1,
        ]);

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/edits.response.json', json_encode($edits->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $edits->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $edits->getResponse());
    }
}
