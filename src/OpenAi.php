<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi;

use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\Interfaces\OpenAiInterface;

/**
 * The provided PHP code defines a class, OpenAi, which acts as the main entry point
 * for interacting with the OpenAI API. This class encapsulates the API key and
 * optional organization key needed for the API, and also creates an instance
 * of the OpenAiRequest class for making HTTP requests.
 *
 * This OpenAi class is used to initialize and configure the interaction with the OpenAI API.
 * It utilizes the Singleton pattern to ensure only one instance of the class exists throughout the application.
 */
final class OpenAi implements OpenAiInterface
{
    public const OPEN_AI_BASE_URI = 'https://api.openai.com/v1';

    /**
     * @var string
     */
    private $openaiApiKey;

    /**
     * @var string
     */
    private $openaiOrganization;

    /**
     * @var string|null
     */
    private $customBaseUri = null;

    /**
     * @var OpenAiRequest
     */
    private $openapiRequest;

    /**
     * @var self
     */
    private static $instance = null;

    public function __construct(string $openaiApiKey, ?string $openaiOrganization = null, ?string $customBaseUri = null)
    {
        $this->setApiKey($openaiApiKey);
        $this->setOrganizationKey($openaiOrganization);
        $this->setOrganizationKey($customBaseUri);
        $this->initOpenAiRequest();
    }

    public function getApiKey(): string
    {
        return $this->openaiApiKey;
    }

    public function getOrganizationKey(): string
    {
        return $this->openaiOrganization;
    }

    public function getOpenAiRequest(): OpenAiRequest
    {
        return $this->openapiRequest;
    }

    /**
     * Gets the base uri.
     *
     * @throws BadResponseException
     *
     * @return     string  The base uri.
     */
    public static function getBaseUri(): ?string
    {
        if (null !== self::$instance && null !== self::$instance->getCustomBaseUri()) {
            return self::$instance->customBaseUri;
        }

        return self::OPEN_AI_BASE_URI;
    }

    /**
     * Gets the base uri.
     *
     * @throws BadResponseException
     *
     * @return     string|null  The base uri.
     */
    public function getCustomBaseUri(): ?string
    {
        if (null !== self::$instance) {
            return self::$instance->customBaseUri;
        }

        return null;
    }

    public function setBaseUri(?string $customBaseUri): self
    {
        if (null !== $customBaseUri && null !== self::$instance) {
            self::$instance->customBaseUri = $customBaseUri;
        }

        return self::$instance;
    }

    public static function updateBaseUri(?string $customBaseUri): self
    {
        if (null !== self::$instance) {
            self::$instance->customBaseUri = $customBaseUri;
        }

        return self::$instance;
    }

    /**
     * @param      string|array<mixed>  $proxy
     *
     * @return     self
     */
    public static function setProxy(mixed $proxy): self
    {
        if (null !== self::$instance) {
            self::$instance->getOpenAiRequest()->addHeaders([
                'proxy' => $proxy,
            ]);
        }

        return self::$instance;
    }

    /**
     * @return     self
     */
    public static function removeProxy(): self
    {
        if (null !== self::$instance) {
            self::removeHeader('proxy');
        }

        return self::$instance;
    }

    /**
     * Adds headers.
     *
     * @param      array<mixed>  $headers
     *
     * @return     self
     */
    public static function addHeaders(?array $headers): self
    {
        if (null !== self::$instance) {
            self::$instance->getOpenAiRequest()->addHeaders($headers);
        }

        return self::$instance;
    }

    /**
     * Adds headers.
     *
     * @param      string  $header
     *
     * @return     self
     */
    public static function removeHeader(string $header): self
    {
        if (null !== self::$instance) {
            self::$instance->getOpenAiRequest()->removeHeader($header);
        }

        return self::$instance;
    }

    /**
     * Gets the headers.
     *
     * @return     array<mixed>.
     */
    public static function getHeaders(): array
    {
        if (null !== self::$instance) {
            return self::$instance->getOpenAiRequest()->getHeaders();
        }

        return [];
    }

    /**
     * Returns the singleton instance of this class.
     *
     * @return     self
     */
    public static function getInstance(): ?self
    {
        return self::$instance;
    }

    private function setApiKey(string $openaiApiKey): self
    {
        $this->openaiApiKey = $openaiApiKey;

        return $this;
    }

    private function setOrganizationKey(?string $openaiOrganization = null): self
    {
        if ($openaiOrganization) {
            $this->openaiOrganization = $openaiOrganization;
        }

        return $this;
    }

    /**
     * Initializes the OpenAiRequest instance.
     *
     * @return     self
     */
    private function initOpenAiRequest(): self
    {
        $this->openapiRequest = OpenAiRequest::getInstance($this);

        return $this;
    }

    /**
     * A static method to initialize the singleton instance. Takes the API and optional organization keys as parameters.
     *
     * @param      string  $openaiApiKey        The openai api key
     * @param      string  $openaiOrganization  The openai organization
     *
     * @return     self
     */
    public static function init(string $openaiApiKey, ?string $openaiOrganization = null, ?string $customBaseUri = null): self
    {
        return self::$instance = new self($openaiApiKey, $openaiOrganization, $customBaseUri);
    }
}
