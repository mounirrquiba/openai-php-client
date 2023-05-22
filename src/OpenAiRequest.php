<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi;

use Generator;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use GuzzleHttp\Psr7\Request;
use MounirRquiba\OpenAi\Abstracts\AbstractService;
use MounirRquiba\OpenAi\Exceptions\FileNotFoundException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * This class is part of a library to interact with the OpenAI API,
 * and it abstracts the details of HTTP communication,
 * allowing other parts of the application to simply provide the necessary
 * parameters and not worry about the specifics of the HTTP protocol or the OpenAI API.
 */
class OpenAiRequest
{
    /**
     * @var OpenAi
     */
    public $openIa;

    /**
     * @var Client
     */
    private $client;

    public const ALLOWED_METHODS = ['get', 'post', 'put', 'patch', 'delete', 'file'];

    /**
     * @var array<string, array<string>|string>
     */
    private $autorizationHeaders = [];

    /**
     * @var array<string, array<string>|string>
     */
    private $headers = [];

    /**
     * @var self
     */
    private static $instance;

    /**
     * Constructs a new instance.
     *
     * @param      OpenAi  $openIa
     */
    public function __construct(OpenAi $openIa)
    {
        $this->openIa = $openIa;

        $this->client = new Client();

        $this->autorizationHeaders['Authorization'] = "Bearer " . $this->openIa->getApiKey();

        if (null !== $this->openIa->getOrganizationKey()) {
            $this->autorizationHeaders['OpenAI-Organization'] = $this->openIa->getOrganizationKey();
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function getAutorizationHeaders(): array
    {
        return $this->autorizationHeaders;
    }

    /**
     * Adds headers.
     *
     * @param      array<mixed>  $headers  The headers
     *
     * @return     self            ( description_of_the_return_value )
     */
    public function addHeaders(?array $headers): self
    {
        $this->headers += $headers;

        return $this;
    }

    /**
     * Adds headers.
     *
     * @param      string  $header
     *
     * @return     self
     */
    public function removeHeader(string $header): self
    {

        if (isset($this->headers[$header])) {
            unset($this->headers[$header]);
        }

        return $this;
    }

    /**
     * @return array<mixed>
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * Accepts an endpoint (an instance of AbstractService), determines the HTTP method, and calls the appropriate method (get(), post(), etc.)
     *
     * @param AbstractService $endPoint
     */
    public function send($endPoint): mixed
    {
        $type = \strtolower($endPoint::OPENAI_METHOD_TYPE);

        if (! in_array($type, self::ALLOWED_METHODS)) {
            throw new \Exception('Method not allowed: [' . $type . '], allowed Methods => [' . \implode(', ', self::ALLOWED_METHODS) . ']');
        }

        return $this->{$type}($endPoint);
    }

    /**
     * @param AbstractService $endPoint
     */
    private function get($endPoint): mixed
    {
        $stream = [];

        if ($endPoint->hasStream()) {
            $this->addHeaders(['stream' => true]);
        }

        /** @var Request */
        $request = new Request(
            'GET',
            $this->openIa->getBaseUri() . $endPoint->getUrl() . $endPoint->getPathParameters(),
            $this->autorizationHeaders + $this->headers
        );

        if ($endPoint->hasStream()) {
            $this->removeHeader('stream');

            return $this->getIterator($this->client->send($request));
        }

        return $this->decode(
            $this->client->send($request),
            $endPoint::OPENAI_DECODE_RESPONSE
        );
    }

    /**
     * @param AbstractService $endPoint
     */
    private function post($endPoint): mixed
    {
        $bodyJson = json_encode($endPoint->getBody());

        if ($bodyJson === false) {
            throw new \Exception("Failed to encode body to JSON in POST request");
        }

        $stream = [];

        if ($endPoint->hasStream()) {
            $this->addHeaders(['stream' => true, 'Accept-Encoding' => 'gzip, deflate, br',]);
        }

        /** @var Request */
        $request = new Request(
            'POST',
            $this->openIa->getBaseUri() . $endPoint->getUrl(),
            $this->autorizationHeaders + $this->headers + ['Content-Type' => "application/json"],
            $bodyJson
        );

        if ($endPoint->hasStream()) {
            $this->removeHeader('stream');

            return $this->getIterator($this->client->send($request, $endPoint->getBody()));
        }

        return $this->decode(
            $this->client->send($request, $endPoint->getBody()),
            $endPoint::OPENAI_DECODE_RESPONSE
        );
    }

    /**
     * @param AbstractService $endPoint
     */
    private function put($endPoint): mixed
    {
        $bodyJson = json_encode($endPoint->getBody());
        if ($bodyJson === false) {
            throw new \Exception("Failed to encode body to JSON in POST request");
        }

        /** @var Request */
        $request = new Request(
            'PUT',
            $this->openIa->getBaseUri() . $endPoint->getUrl(),
            $this->autorizationHeaders + $this->headers + ['Content-Type' => "application/json"],
            $bodyJson
        );

        return $this->decode(
            $this->client->send($request),
            $endPoint::OPENAI_DECODE_RESPONSE
        );
    }

    /**
     * @param AbstractService $endPoint
     */
    private function patch($endPoint): mixed
    {
        $bodyJson = json_encode($endPoint->getBody());
        if ($bodyJson === false) {
            throw new \Exception("Failed to encode body to JSON in POST request");
        }

        /** @var Request */
        $request = new Request(
            'PATCH',
            $this->openIa->getBaseUri() . $endPoint->getUrl(),
            $this->autorizationHeaders + $this->headers + ['Content-Type' => "application/json"],
            $bodyJson
        );

        return $this->decode(
            $this->client->send($request),
            $endPoint::OPENAI_DECODE_RESPONSE
        );
    }

    /**
     * @param AbstractService $endPoint
     */
    private function delete($endPoint): mixed
    {
        /** @var Request */
        $request = new Request(
            'DELETE',
            $this->openIa->getBaseUri() . $endPoint->getUrl(),
            $this->autorizationHeaders + $this->headers
        );

        return $this->decode(
            $this->client->send($request),
            $endPoint::OPENAI_DECODE_RESPONSE
        );
    }

    /**
     * @param AbstractService $endPoint
     */
    private function file($endPoint): mixed
    {
        $request = new Request(
            'POST',
            $this->openIa->getBaseUri() . $endPoint->getUrl(),
            $this->autorizationHeaders + $this->headers,
        );

        return $this->decode(
            $this->client->send(
                $request,
                $this->arrayToMultipart($endPoint->getBody())
            ),
            $endPoint::OPENAI_DECODE_RESPONSE
        );
    }

    /**
     * These methods are for testing, allowing the Guzzle client to be replaced or reset
     *
     * @param array<mixed> $headers
     *
     * @return self
     */
    public function setMockClient(array $headers): self
    {
        $this->client = new Client($headers);

        return $this;
    }

    /**
     * These methods are for testing, allowing the Guzzle client to be replaced or reset
     *
     * @return self
     */
    public function clearMockClient(): self
    {
        $this->client = new Client();

        return $this;
    }

    /**
     * Decodes the response from the OpenAI API.
     *
     * @param      ResponseInterface  $response
     * @param      bool                       $isJsonResponse
     *
     * @return     int|float|string|bool|null|array<mixed>
     */
    public function decode(ResponseInterface $response, bool $isJsonResponse = true): int|float|string|bool|null|array
    {
        $body = $response->getBody();

        return $isJsonResponse
            ? (array) \json_decode($body->getContents(), true)
            : (string) $body->getContents();
    }

    /**
     * @param      OpenAi  $openIa
     *
     * @return     self
     */
    public static function getInstance(OpenAi $openIa): self
    {
        if (null === self::$instance) {
            self::$instance = new self($openIa);
            ;
        }

        return self::$instance;
    }

    /**
     * { function_description }
     *
     * @param      array<int|string, mixed>                 $body
     *
     * @throws     FileNotFoundException
     *
     * @return     array<int|string, mixed>
     */
    private function arrayToMultipart(array $body): array
    {
        $uploadKeys = ['image', 'mask', 'file'];

        $json = [];
        $multipart = [];

        foreach ($body as $key => $value) {
            if (gettype($value) === 'array' || gettype($value) === 'object') {
                continue;
            }

            if (\in_array($key, $uploadKeys)) {
                // @phpstan-ignore-next-line
                if (! file_exists((string) $value)) {
                    // @phpstan-ignore-next-line
                    throw new FileNotFoundException((string) $value, self::class);
                }

                $multipart[] = [
                    'Content-type' => 'multipart/form-data',
                    'name' => $key,
                    // @phpstan-ignore-next-line
                    'contents' => Psr7\Utils::tryFopen((string) $value, 'r'),
                ];
            } else {
                $multipart[] = [
                    'name' => $key,
                    'contents' => $value,
                ];
            }
        }

        return [ 'multipart' => $multipart];
    }

    /**
     * @param      ResponseInterface     $response
     *
     * @return     Generator
     */
    public function getIterator(ResponseInterface $response): Generator
    {
        $done = '[DONE]';
        $prefix = 'data:';

        while (! $response->getBody()->eof()) {
            $line = $this->extractLineFomStream($response->getBody());

            $prefix !== substr($line, 0, 5);
            if ($prefix !== substr($line, 0, 5)) {
                continue;
            }

            $data = trim(substr($line, strlen($prefix)));

            if ($done === $data) {
                break;
            }

            yield json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        }
    }

    /**
     * @param      StreamInterface  $stream
     *
     * @return     string
     */
    private function extractLineFomStream(StreamInterface $stream): string
    {
        $buffer = '';

        while (! $stream->eof()) {
            $byte = $stream->read(1);

            if ('' === $byte) {
                return $buffer;
            }

            $buffer .= $byte;

            if (PHP_EOL === $byte) {
                break;
            }
        }

        return $buffer;
    }
}
