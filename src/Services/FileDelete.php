<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;

/**
 * Files are used to upload documents that can be used with features like Fine-tuning.
 *
 * Delete a file.
 */
final class FileDelete extends AbstractService
{
    /**
     * {@inheritdoc}
     */
    public const OPENAI_DECODE_RESPONSE = true;

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ID_NAME = 'file_id';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ENDPOINT = '/files/{' . self::OPENAI_ID_NAME . '}';

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

    public function __construct(string $fileId = null)
    {
        parent::__construct($fileId);
    }

    public function setFile(string $fileId): self
    {
        $this->setId($fileId);

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->getId();
    }
}
