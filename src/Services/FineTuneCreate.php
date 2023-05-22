<?php

declare(strict_types=1);

namespace MounirRquiba\OpenAi\Services;

use MounirRquiba\OpenAi\Abstracts\AbstractService;
use MounirRquiba\OpenAi\Validators\ArrayValidator;
use MounirRquiba\OpenAi\Validators\BooleanValidator;
use MounirRquiba\OpenAi\Validators\IntegerValidator;
use MounirRquiba\OpenAi\Validators\NumberValidator;
use MounirRquiba\OpenAi\Validators\RequiredValidator;
use MounirRquiba\OpenAi\Validators\StringValidator;

/**
 * Manage fine-tuning jobs to tailor a model to your specific training data.
 *
 * Related guide: https://platform.openai.com/docs/guides/fine-tuning
 *
 * Creates a job that fine-tunes a specified model from a given dataset.
 * Response includes details of the enqueued job including job status
 * and the name of the fine-tuned models once complete.
 */
final class FineTuneCreate extends AbstractService
{
    /**
     * {@inheritdoc}
     */
    public const OPENAI_DECODE_RESPONSE = true;

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ID_NAME = null;

    /**
     * {@inheritdoc}
     */
    public const OPENAI_ENDPOINT = '/fine-tunes';

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PARAMETERS = [
        /**
         * training_file string Required
         *
         * The ID of an uploaded file that contains training data.
         */
        [
            'name' => 'training_file', RequiredValidator::NAME => true, 'type' => StringValidator::NAME,
        ],

        /**
         * validation_file string Optional
         *
         * The ID of an uploaded file that contains validation data.
         */
        [
            'name' => 'validation_file', RequiredValidator::NAME => false, 'type' => StringValidator::NAME,
        ],

        /**
         * model string Optional
         *
         * The ID of an uploaded file that contains validation data.
         */
        [
            'name' => 'model', RequiredValidator::NAME => false, 'type' => StringValidator::NAME, 'default' => 'curie',
        ],

        /**
         * n_epochs integer Optional
         *
         * The number of epochs to train the model for. An epoch refers to one full cycle through the training dataset.
         */
        [
            'name' => 'n_epochs', RequiredValidator::NAME => false, 'type' => IntegerValidator::NAME, 'default' => 4,
        ],

        /**
         * batch_size integer Optional Defaults to null
         *
         * The batch size to use for training. The batch size is the number of training examples used to train a single forward and backward pass.
         * By default, the batch size will be dynamically configured to be ~0.2% of the number of examples in the training set,
         * capped at 256 - in general, we've found that larger batch sizes tend to work better for larger datasets.
         */
        [
            'name' => 'batch_size', RequiredValidator::NAME => false, 'type' => IntegerValidator::NAME,
        ],

        /**
         * batch_size integer Optional Defaults to null
         *
         * The batch size to use for training. The batch size is the number of training examples used to train a single forward and backward pass.
         * By default, the batch size will be dynamically configured to be ~0.2% of the number of examples in the training set,
         * capped at 256 - in general, we've found that larger batch sizes tend to work better for larger datasets.
         */
        [
            'name' => 'batch_size', RequiredValidator::NAME => false, 'type' => IntegerValidator::NAME,
        ],

        /**
         * learning_rate_multiplier number Optional Defaults to null
         *
         * The batch size to use for training. The batch size is the number of training examples used to train a single forward and backward pass.
         * By default, the batch size will be dynamically configured to be ~0.2% of the number of examples in the training set,
         * capped at 256 - in general, we've found that larger batch sizes tend to work better for larger datasets.
         */
        [
            'name' => 'learning_rate_multiplier', RequiredValidator::NAME => false, 'type' => NumberValidator::NAME,
        ],

        /**
         * prompt_loss_weight number Optional Defaults to 0.01
         *
         * The weight to use for loss on the prompt tokens. This controls how much the model tries to learn to generate the
         * prompt (as compared to the completion which always has a weight of 1.0), and can add a stabilizing effect to training when completions are short.
         * If prompts are extremely long (relative to completions), it may make sense to reduce this weight so as to
         * avoid over-prioritizing learning the prompt.
         */
        [
            'name' => 'prompt_loss_weight', RequiredValidator::NAME => false, 'type' => NumberValidator::NAME,
        ],

        /**
         * compute_classification_metrics boolean Optional Defaults to false
         *
         * If set, we calculate classification-specific metrics such as accuracy and F-1 score using the validation set
         * at the end of every epoch. These metrics can be viewed in the results file.
         * In order to compute classification metrics, you must provide a validation_file.
         * Additionally, you must specify classification_n_classes for multiclass classification or classification_positive_class for binary classification.
         */
        [
            'name' => 'compute_classification_metrics', RequiredValidator::NAME => false, 'type' => BooleanValidator::NAME,
        ],

        /**
         * classification_n_classes integer Optional Defaults to null
         *
         * The number of classes in a classification task.
         * This parameter is required for multiclass classification.
         */
        [
            'name' => 'classification_n_classes', RequiredValidator::NAME => false, 'type' => IntegerValidator::NAME,
        ],

        /**
         * classification_positive_class string Optional Defaults to null
         *
         * The positive class in binary classification.
         * This parameter is needed to generate precision, recall, and F1 metrics when doing binary classification.
         */
        [
            'name' => 'classification_positive_class', RequiredValidator::NAME => false, 'type' => StringValidator::NAME,
        ],

        /**
         * classification_betas array Optional Defaults to null
         *
         * If this is provided, we calculate F-beta scores at the specified beta values.
         * The F-beta score is a generalization of F-1 score. This is only used for binary classification.
         * With a beta of 1 (i.e. the F-1 score), precision and recall are given the same weight.
         * A larger beta score puts more weight on recall and less on precision. A smaller beta score puts more weight on precision and less on recall.
         */
        [
            'name' => 'classification_betas', RequiredValidator::NAME => false, 'type' => ArrayValidator::NAME,
        ],

        /**
         * suffix string Optional Defaults to null
         *
         * A string of up to 40 characters that will be added to your fine-tuned model name.
         * For example, a suffix of "custom-model-name" would produce a model name like ada:ft-your-org:custom-model-name-2022-02-15-04-21-04.
         */
        [
            'name' => 'suffix', RequiredValidator::NAME => false, 'type' => StringValidator::NAME, 'max' => 40,
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_PATH_PARAMETERS = [];

    /**
     * {@inheritdoc}
     */
    public const OPENAI_METHOD_TYPE = 'POST';
}
