<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Abstracts;

use GuzzleHttp\Exception\ClientException;
use MounirRquiba\OpenAi\Exceptions\BadResponseException;
use MounirRquiba\OpenAi\Exceptions\InvalidParameterException;
use MounirRquiba\OpenAi\Exceptions\RequiredParameterException;
use MounirRquiba\OpenAi\OpenAi;
use MounirRquiba\OpenAi\Validators\AnyValidator;
use MounirRquiba\OpenAi\Validators\ArrayValidator;
use MounirRquiba\OpenAi\Validators\BooleanValidator;
use MounirRquiba\OpenAi\Validators\EnumValidator;
use MounirRquiba\OpenAi\Validators\IntegerValidator;
use MounirRquiba\OpenAi\Validators\JsonLinesValidator;
use MounirRquiba\OpenAi\Validators\MessagesValidator;
use MounirRquiba\OpenAi\Validators\NumberValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use MounirRquiba\OpenAi\Validators\StringOrArrayValidator;
use MounirRquiba\OpenAi\Validators\StringValidator;

/**
 * The AbstractService class that serves as a blueprint for creating service classes
 * that interact with the OpenAI API.
 *
 * This class contains common methods and properties which
 * can be reused across different concrete service classes.
 *
 * This class should be extended by other concrete service classes,
 * which would define the constants and use the methods provided
 * by this abstract class to interact with the OpenAI API.
 * The concrete classes would provide specific implementation details
 * like the endpoint, parameters, method type, etc. required for their respective API endpoints.
 */
abstract class AbstractService
{
    /**
     * @var bool
     */
    public const OPENAI_DECODE_RESPONSE = true;

    /**
     * @var ?string
     */
    public const OPENAI_ID_NAME = null;

    /**
     * @var string
     */
    public const OPENAI_ENDPOINT = '';

    public const OPENAI_PARAMETERS = [];

    public const OPENAI_PATH_PARAMETERS = [];

    /**
     * @var string
     */
    public const OPENAI_METHOD_TYPE = 'GET';

    /**
     * An array to store the body of the HTTP request to be sent to the OpenAI API.
     *
     * @var array<int|string, int|string|array<mixed>|bool>
     */
    private $body = [];

    /**
     * An array to store the query of the HTTP request to be sent to the OpenAI API.
     *
     * @var array<int|string, int|string|array<mixed>|bool>
     */
    private $query = [];

    /**
     * A string to store the ID for the specific endpoint.
     *
     * @var string|null
     */
    private $id;

    /**
     * @var bool
     */
    private $isStream;

    /**
     * This property is used to store the response received from the OpenAI API.
     *
     * @var array<string, string>|mixed
     */
    private $response = null;

    /**
     * An instance of the OpenAi class.
     *
     * @var ?OpenAi
     */
    private $openIa;

    public function __construct(string $id = null)
    {
        $this->id = $id;

        $instance = OpenAi::getInstance();

        if (null === $instance) {
            $apiKey = getenv('OPENAI_API_KEY');
            $organizationKey = getenv('OPENAI_ORGANIZATION_KEY');

            if ($apiKey) {
                $this->openIa = OpenAi::init($apiKey, $organizationKey ? $organizationKey : null);
            } else {
                throw new \Exception('OPENAI_API_KEY key not set into your env');
            }
        }

        $this->openIa = OpenAi::getInstance();

    }

    /**
     * @param      array<int|string, int|string|array<mixed>|bool>   $body
     *
     * @throws     InvalidParameterException
     * @throws     RequiredParameterException
     *
     * @return     array<mixed>
     */
    public function setBody(array $body): array
    {
        if (empty($this::OPENAI_PARAMETERS)) {
            return [];
        }

        $this->body = [];

        $this->validateParamaters($this::OPENAI_PARAMETERS, $body);

        return $this->body;
    }

    /**
     * Sets the request body, validating each parameter with appropriate validators.
     *
     * @param      array<int|string, int|string|array<mixed>|bool>        $query
     *
     * @throws     InvalidParameterException
     * @throws     RequiredParameterException
     *
     * @return     array<int|string, int|string|array<mixed>|bool>
     */
    public function setQuery(array $query): array
    {
        if (empty($this::OPENAI_PATH_PARAMETERS)) {
            return [];
        }

        $this->query = [];

        $this->validateParamaters($this::OPENAI_PATH_PARAMETERS, $query, 'query');

        return $this->query;
    }

    /**
     * @param      array<int|string, array<string, string>>    $parameters
     * @param      array<int|string, int|string|array<mixed>|bool>      $body
     * @param      string                                      $key
     *
     * @throws     InvalidParameterException
     * @throws     RequiredParameterException
     */
    public function validateParamaters(array $parameters, array $body, string $key = 'body'): void
    {
        if (! property_exists($this, $key)) {
            return;
        }

        foreach($parameters as $config) {

            $value = $body[$config['name']] ?? null;

            if (! RequiredValidator::isValid($config, $value)) {
                throw new RequiredParameterException($config['name'], $config['type'], $this::class);
            }

            if ($value) {
                switch ($config['type']) {
                    case ArrayValidator::NAME:
                        if (! ArrayValidator::isValid($config, $value)) {
                            throw new InvalidParameterException($config['name'], $config['type'], $this::class);
                        }

                        $this->{$key}[$config['name']] = $value;

                        break;

                    case BooleanValidator::NAME:
                        if (! BooleanValidator::isValid($config, $value)) {
                            throw new InvalidParameterException($config['name'], $config['type'], $this::class);
                        }

                        $this->isStream = in_array($config['name'], ['stream']);

                        $this->{$key}[$config['name']] = $value;

                        break;

                    case StringOrArrayValidator::NAME:
                        if (! StringOrArrayValidator::isValid($config, $value)) {
                            throw new InvalidParameterException($config['name'], $config['type'], $this::class);
                        }

                        $this->{$key}[$config['name']] = $value;

                        break;

                    case MessagesValidator::NAME:
                        if (! MessagesValidator::isValid($config, $value)) {
                            throw new InvalidParameterException($config['name'], $config['type'], $this::class);
                        }

                        $this->{$key}[$config['name']] = $value;

                        break;

                    case StringValidator::NAME:
                        if (! StringValidator::isValid($config, $value)) {
                            throw new InvalidParameterException($config['name'], $config['type'], $this::class);
                        }

                        $this->{$key}[$config['name']] = $value;

                        break;

                    case IntegerValidator::NAME:
                        if (! IntegerValidator::isValid($config, $value)) {
                            throw new InvalidParameterException($config['name'], $config['type'], $this::class);
                        }

                        $this->{$key}[$config['name']] = $value;

                        break;

                    case NumberValidator::NAME:
                        if (! NumberValidator::isValid($config, $value)) {
                            throw new InvalidParameterException($config['name'], $config['type'], $this::class);
                        }

                        $this->{$key}[$config['name']] = $value;

                        break;

                    case EnumValidator::NAME:
                        if (! EnumValidator::isValid($config, $value)) {
                            throw new InvalidParameterException($config['name'], $config['type'], $this::class);
                        }

                        $this->{$key}[$config['name']] = $value;

                        break;

                    case JsonLinesValidator::NAME:
                        if (! JsonLinesValidator::isValid($config, $value)) {
                            throw new InvalidParameterException($config['name'], $config['type'], $this::class);
                        }

                        $this->{$key}[$config['name']] = $value;

                        break;

                    case AnyValidator::NAME:
                        if (! AnyValidator::isValid($config, $value)) {
                            throw new InvalidParameterException($config['name'], $config['type'], $this::class);
                        }

                        $this->{$key}[$config['name']] = $value;

                        break;
                }
            }
        }
    }

    /**
     * Returns the request body.
     *
     * @return array<int|string, int|string|array<mixed>|bool>
     */
    public function getBody(): array
    {
        return $this->body;
    }

    protected function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    protected function getId(): string|null
    {
        return $this->id;
    }

    /**
     * Returns the URL for the OpenAI API endpoint.
     *
     * @return     string  The url.
     */
    public function getUrl(): string
    {
        if (null !== $this::OPENAI_ID_NAME) {
            return \str_replace('{' . $this::OPENAI_ID_NAME . '}', (string) $this->getId(), $this::OPENAI_ENDPOINT);
        }

        return $this::OPENAI_ENDPOINT;
    }

    /**
     * @param      array<int|string, int|string|array<mixed>|bool>  $query
     *
     * @return     self
     */
    public function setPathParameters(array $query = []): self
    {
        $this->setQuery($query);

        return $this;
    }

    /**
     * Returns path paramters.
     *
     * @return string
     */
    public function getPathParameters(): string
    {
        if (empty($this->query)) {
            return '';
        }

        return '?' . implode('&', array_map(
            function ($v, $k) {
                return is_array($v)
                    ? $k . '[]=' . implode('&' . $k . '[]=', $v)
                    : (is_bool($v)
                        ? $k . '=' . ($v
                            ? 'true'
                            : false)
                        : $k . '=' . $v);
            },
            $this->query,
            array_keys($this->query)
        ));
    }

    /**
     * Sends a request to the OpenAI API, sets the response, and handles exceptions.
     *
     * @param      array<int|string, int|string|array<mixed>|bool>                   $body
     *
     * @throws     BadResponseException
     *
     * @return     self
     */
    public function create(array $body = []): self
    {
        $this->setBody($body);

        try {
            if (null !== $this->openIa) {
                $this->response = $this->openIa->getOpenAiRequest()->send($this);
            }
        } catch(ClientException $e) {
            $response = $e->getResponse();

            throw new BadResponseException('Status Code: ' . $response->getStatusCode() . ' | ' . (string) $response->getBody());
        }

        return $this;
    }

    /**
     * Returns the response received from the OpenAI API.
     *
     * @return     mixed
     */
    public function getResponse(): mixed
    {
        return $this->response;
    }

    /**
     * @return     bool
     */
    public function hasStream(): ?bool
    {
        return $this->isStream;
    }

    /**
     * Returns the OpenAi instance.
     *
     * @return     ?OpenAi
     */
    public function getOpenAi(): ?OpenAi
    {
        return $this->openIa;
    }
}
