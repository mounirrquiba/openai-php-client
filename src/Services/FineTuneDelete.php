<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;

final class FineTuneDelete extends AbstractService
{
    /**
     * {@inheritdoc}
     */
    public const OPENAI_DECODE_RESPONSE = true;

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ID_NAME = 'fine_tuned_model';

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
    public const OPENAI_METHOD_TYPE = 'DELETE';

    public function setFineTuneModel(string $fineTune): self
    {
        $this->setId($fineTune);

        return $this;
    }

    public function getFineTuneModel(): string|null
    {
        return $this->getId();
    }
}
