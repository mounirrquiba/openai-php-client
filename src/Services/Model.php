<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;

/**
 * List and describe the various models available in the API.
 * You can refer to the Models documentation to understand what models are available and the differences between them.
 *
 * Documentation: https://platform.openai.com/docs/models
 *
 * Given a prompt, the model will return one or more predicted completions
 * and can also return the probabilities of alternative tokens at each position.
 */
final class Model extends AbstractService
{
    /**
     * {@inheritdoc}
     */
    public const OPENAI_DECODE_RESPONSE = true;

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ID_NAME = 'model';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ENDPOINT = '/models/{' . self::OPENAI_ID_NAME . '}';

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

    public function setModel(string $model): self
    {
        $this->setId($model);

        return $this;
    }

    public function getModel(): string|null
    {
        return $this->getId();
    }
}
