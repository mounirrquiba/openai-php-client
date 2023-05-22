<?php

declare(strict_types=1);

use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\OpenAiRequest;
use PHPUnit\Framework\TestCase;

class OpenAiTest extends TestCase
{
    public function testInitOpenAiInstance(): void
    {
        $openIa = OpenAi::init('apikey', 'organizationKey');

        $this->assertInstanceOf(OpenAi::class, $openIa);
        $this->assertInstanceOf(OpenAiRequest::class, $openIa->getOpenAiRequest());


        $this->assertSame($openIa->getApiKey(), 'apikey');
        $this->assertSame($openIa->getOrganizationKey(), 'organizationKey');
    }
}
