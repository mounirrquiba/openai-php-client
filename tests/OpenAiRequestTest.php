<?php

declare(strict_types=1);

use GuzzleHttp\Client;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use PHPUnit\Framework\TestCase;

class OpenAiRequestTest extends TestCase
{
    public function testInitOpenAiRequestInstance(): void
    {
        $openIa = OpenAi::init('apikey', 'organizationKey');

        $openAiRequest = OpenAiRequest::getInstance($openIa);

        $this->assertInstanceOf(OpenAiRequest::class, $openAiRequest);
        $this->assertInstanceOf(Client::class, $openAiRequest->getClient());

        $this->assertSame(count($openAiRequest->getAutorizationHeaders()), 2);
        $this->assertSame($openAiRequest->getAutorizationHeaders()['Authorization'], 'Bearer apikey');
        $this->assertSame($openAiRequest->getAutorizationHeaders()['OpenAI-Organization'], 'organizationKey');
    }
}
