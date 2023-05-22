<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;

/**
 * [Deprecated]
 * Retrieves a model instance, providing basic information about it such as the owner and availability.
 */
final class Engine extends AbstractService
{
    /**
     * {@inheritdoc}
     */
    public const OPENAI_DECODE_RESPONSE = true;

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ID_NAME = 'engine_id';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ENDPOINT = '/engines/{' . self::OPENAI_ID_NAME . '}';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PARAMETERS = [];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PATH_PARAMETERS = [];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_METHOD_TYPE = 'GET';

    public function __construct(string $model = null)
    {
        parent::__construct($model);
    }

    public function setEngineId(string $model): self
    {
        $this->setId($model);

        return $this;
    }

    public function getEngineId(): string|null
    {
        return $this->getId();
    }
}
