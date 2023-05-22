<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use MounirRquiba\OpenAi\Services\Moderations;
use PHPUnit\Framework\TestCase;

final class ModerationsTest extends TestCase
{
    private $openIa;

    protected $apiKey = 'apiKey';
    protected $organizationKey = 'organizationKey';

    protected function setUp(): void
    {
        $this->openIa = OpenAi::init($this->apiKey, $this->organizationKey);
    }

    public function testModerationsInstance(): void
    {
        $moderations = new Moderations();

        $this->assertInstanceOf(Moderations::class, $moderations);

        $this->assertInstanceOf(OpenAi::class, $moderations->getOpenAi());
        $this->assertInstanceOf(OpenAiRequest::class, $moderations->getOpenAi()->getOpenAiRequest());
        $this->assertInstanceOf(Client::class, $moderations->getOpenAi()->getOpenAiRequest()->getClient());

        $this->assertSame($moderations->getOpenAi()->getApiKey(), $this->apiKey);
        $this->assertSame($moderations->getOpenAi()->getOrganizationKey(), $this->organizationKey);
    }

    public function testModerationsClientExceptionRequest(): void
    {
        $this->expectException(BadResponseException::class);

        $moderations = (new Moderations())->create([
            'input' => ['la vie est belle', "il va le tuer"],
        ]);
    }

    public function testModerationsResponse(): void
    {
        $message = file_get_contents(__DIR__ . '/Fixtures/moderations.response.json');

        $response = new Response(200, [], $message);

        $this->openIa->getOpenAiRequest()->setMockClient([
            'handler' => HandlerStack::create(
                new MockHandler([ $response, $response ])
            ),
        ]);

        $moderations = (new Moderations())->create([
            'input' => ['la vie est belle', "il va le tuer"],
        ]);

        $this->openIa->getOpenAiRequest()->clearMockClient();

        // file_put_contents(__DIR__ . '/Fixtures/moderations.response.json', json_encode($moderations->getResponse(), JSON_PRETTY_PRINT) . PHP_EOL);
        // var_dump('========================', $moderations->getResponse(), '========================');

        $this->assertSame(json_decode($message, true), $moderations->getResponse());
    }
}
