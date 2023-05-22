# OpenAI API Client in PHP (community-maintained)
__This library is a component-oriented, extensible client library for the OpenAI API. It's designed to be faster and more memory efficient than traditional PHP libraries.__

## Installation

You can install the package via composer:

```bash

// PHP 8.0, 8.1, 8.2
composer require mounirrquiba/openai

// PHP 7.2.34, 7.2.5, 7.3, 7.4.
composer require mounirrquiba/openaiphp7
```

# Table of Contents

* [Quick Start](#quick-start)
    * [Keys](#keys-configuration)
    * [Custom API URL](#custom-api-url-configuration)
    * [Proxy](#proxy-configuration)
    * [Headers](#headers-configuration)
* [Services](#services)
    * [Models](#models)
        * [List models](#list-models)
        * [Retrieve model](#retrieve-model)
    * [Completions](#completions)
        * [Create completions](#create-completions)
        * [Create stream completions](#create-stream-completions)
    * [Chat](#chat)
        * [Create chat completion](#create-chat-completion)
        * [Create stream chat completion](#create-stream-chat-completion)
    * [Edits](#edits)
        * [Create edit](#create-edit)
    * [Images](#images)
        * [Create image](#create-image)
        * [Create image edit](#create-image-edit)
        * [Create image variation](#create-image-variation)
    * [Embeddings](#embeddings)
        * [Create embeddings](#create-embeddings)
    * [Audio](#audio)
        * [Create transcription](#create-embeddings)
        * [Create translation](#create-translation)
    * [Files](#files)
        * [List files](#list-files)
        * [Upload file](#upload-file)
        * [Delete file](#delete-file)
        * [Retrieve file](#retrieve-file)
        * [Retrieve file content](#retrieve-file-content)
    * [Fine-tunes](#fine-tunes)
        * [Create fine-tune](#create-fine-tune)
        * [List fine-tunes](#list-fine-tunes)
        * [Retrieve fine-tune](#retrieve-fine-tune)
        * [Cancel fine-tune](#cancel-fine-tune)
        * [List fine-tune events](#list-fine-tune-events)
        * [List fine-tune events with stream](#list-fine-tune-events-with-stream)
        * [Delete fine-tune model](#delete-fine-tune-model)
    * [Moderations](#moderations)
        * [Create moderation](#create-moderation)
* [Exceptions](#exceptions)
* [Tests](#tests)
* [Licence](#licence)



## Quick Start

### Keys configuration
> You can store you api key and organization key into your env

_Powershell_

```powershell
$Env:OPENAI_API_KEY = "sk-7nUNKsoMg...dxQYa5xN0BlDu"
$Env:OPENAI_ORGANIZATION_KEY = "org-bYCY6S...Po6sKXi"
```
_Cmd_
```cmd
set OPENAI_API_KEY=sk-7nUNKsoMg...dxQYa5xN0BlDu
set OPENAI_ORGANIZATION_KEY=org-bYCY6S...Po6sKXi
```

_Linux or macOS_

```shell
# create ~/.profile if not exists
echo 'export OPENAI_API_KEY=sk-7nUNKsoMg...dxQYa5xN0BlDu' >> ~/.profile
echo 'export OPENAI_ORGANIZATION_KEY=org-bYCY6S...Po6sKXi' >> ~/.profile
source ~/.profile
```
> Alternatively you can set it in your code, you only need to do this once. If you have already put the variables in your env this step is not necessary
```php
use MounirRquiba\OpenAi\OpenAi;

$apiKey = 'sk-7nUNKsoMg...dxQYa5xN0BlDu';
$organizationKey = 'org-bYCY6S...Po6sKXi';

// For users without organization
OpenAi::init($apiKey);

// For users with organization
OpenAi::init($apiKey, $organizationKey);
```

### Custom API URL configuration
> If you don't use OpenAI endpoint you can change it
```php
// Set your custom api url
OpenAi::updateBaseUri('https://myurl.com');

// Get value of api url
OpenAi::getBaseUri();
```

### Proxy configuration
```php

// Set proxy
OpenAi::setProxy([
    'http'  => 'tcp://localhost:8125',
    'https' => 'tcp://localhost:9124'
]);

// Remove proxy configuation
OpenAi::removeProxy();
```

### Headers configuration
```php
// Add your custom headers
OpenAi::addHeaders([ 'myCustomKey' => 'myCustomValue']);

// Get value of custom headers
OpenAi::getHeaders();

// Remove specific custom header
OpenAi::removeHeader('myCustomKey');
```
[Back to top](#installation)

# Services
## Models
> **Lists the currently available models, and provides basic information about each one such as the owner and availability.**
### Request (models)
```php
use MounirRquiba\OpenAi\Services\Models;

$models = (new Models())
    ->create();

var_dump($models->getResponse());
```
### Response (models)

```php
array(2) {
  ["object"]=>
  string(4) "list"
  ["data"]=>
  array(69) {
    [0]=>
    array(7) {
      ["id"]=>
      string(9) "whisper-1"
      ["object"]=>
      string(5) "model"
      ["created"]=>
      int(1677532384)
      ["owned_by"]=>
      string(15) "openai-internal"
      ["permission"]=>
      array(1) {
        [0]=>
        array(12) {
          ["id"]=>
          string(34) "modelperm-KlsZlfft3Gma8pI6A8rTnyjs"
          ["object"]=>
          string(16) "model_permission"
          ["created"]=>
          int(1683912666)
          ["allow_create_engine"]=>
          bool(false)
          ["allow_sampling"]=>
          bool(true)
          ["allow_logprobs"]=>
          bool(true)
          ["allow_search_indices"]=>
          bool(false)
          ["allow_view"]=>
          bool(true)
          ["allow_fine_tuning"]=>
          bool(false)
          ["organization"]=>
          string(1) "*"
          ["group"]=>
          NULL
          ["is_blocking"]=>
          bool(false)
        }
      }
      ["root"]=>
      string(9) "whisper-1"
      ["parent"]=>
      NULL
    }
    ...
  }
}
```

[Back to top](#installation)

## Model
> **Retrieves a model instance, providing basic information about the model such as the owner and permissioning.**

### Request (model)
```php
use MounirRquiba\OpenAi\Services\Model;

$model = (new Model())
   ->setModel('text-davinci-003')
   ->create();

// or
$model = (new Model('text-davinci-003')
   ->create();

var_dump($model->getResponse());
```
### Response (model)
```php
array(7) {
  ["id"]=>
  string(16) "text-davinci-003"
  ["object"]=>
  string(5) "model"
  ["created"]=>
  int(1669599635)
  ["owned_by"]=>
  string(15) "openai-internal"
  ["permission"]=>
  array(1) {
    [0]=>
    array(12) {
      ["id"]=>
      string(34) "modelperm-07PTNFc1zx2v6uxZTQm1reTm"
      ["object"]=>
      string(16) "model_permission"
      ["created"]=>
      int(1684343723)
      ["allow_create_engine"]=>
      bool(false)
      ["allow_sampling"]=>
      bool(true)
      ["allow_logprobs"]=>
      bool(true)
      ["allow_search_indices"]=>
      bool(false)
      ["allow_view"]=>
      bool(true)
      ["allow_fine_tuning"]=>
      bool(false)
      ["organization"]=>
      string(1) "*"
      ["group"]=>
      NULL
      ["is_blocking"]=>
      bool(false)
    }
  }
  ["root"]=>
  string(16) "text-davinci-003"
  ["parent"]=>
  NULL
}
```

[Back to top](#installation)


## Completions
> **Given a prompt, the model will return one or more predicted completions, and can also return the probabilities of alternative tokens at each position.**

## Create completions
> **Creates a completion for the provided prompt and parameters.**

### Request (Create completions)
```php
use MounirRquiba\OpenAi\Services\Completions;

$completions = (new Completions())
    ->create([
        'model' => 'text-davinci-003',
        'prompt' => 'OpenAi is ',
    ]);

var_dump($completions->getResponse());
```
### Response (Create completions)
```php
array(6) {
  ["id"]=>
  string(34) "cmpl-7HEtOf4c7Qfq9XTszOgSdT0lDsnTA"
  ["object"]=>
  string(15) "text_completion"
  ["created"]=>
  int(1684343178)
  ["model"]=>
  string(16) "text-davinci-003"
  ["choices"]=>
  array(1) {
    [0]=>
    array(4) {
      ["text"]=>
      string(82) " an artificial intelligence (AI) company that provides a suite of services, tools,"
      ["index"]=>
      int(0)
      ["logprobs"]=>
      NULL
      ["finish_reason"]=>
      string(6) "length"
    }
  }
  ["usage"]=>
  array(3) {
    ["prompt_tokens"]=>
    int(5)
    ["completion_tokens"]=>
    int(16)
    ["total_tokens"]=>
    int(21)
  }
}
```

[Back to top](#installation)


## Create stream completions
> **Creates a completion for the provided prompt and parameters with stream.**

### Request (Create stream completions)
```php
use MounirRquiba\OpenAi\Services\Completions;

$prompt = 'OpenAi is';
$completions = (new Completions())
    ->create([
        'model' => 'text-davinci-003',
        'prompt' => $prompt,
        'stream' => true
    ]);

echo $prompt;

foreach ($completions->getResponse() as $value) {
    if (isset($value['choices'][0]['text'])) {
        echo $value['choices'][0]['text'];
    }
}

echo PHP_EOL;
```
### Response (Create stream completions)
```text
OpenAi is an Artificial Intelligence company founded in December, 2015 that is based out of San Francisco
```

[Back to top](#installation)


## Chat
> **Given a list of messages describing a conversation, the model will return a response.**

## Create chat completion
> **Creates a model response for the given chat conversation.**

### Request (Create chat completion)
```php
use MounirRquiba\OpenAi\Services\Completions;

$chat = (new Chat())
    ->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'user', 'content' => "Je ne comprends pas OpenAi tu peux m'aider ?"]
        ]
    ]);

var_dump($chat->getResponse());
```
### Response (Create chat completion)
```php
array(6) {
  ["id"]=>
  string(38) "chatcmpl-7HEwwJrGnDKkq1vQv2JrOByCxxNLw"
  ["object"]=>
  string(15) "chat.completion"
  ["created"]=>
  int(1684343398)
  ["model"]=>
  string(18) "gpt-3.5-turbo-0301"
  ["usage"]=>
  array(3) {
    ["prompt_tokens"]=>
    int(21)
    ["completion_tokens"]=>
    int(136)
    ["total_tokens"]=>
    int(157)
  }
  ["choices"]=>
  array(1) {
    [0]=>
    array(3) {
      ["message"]=>
      array(2) {
        ["role"]=>
        string(9) "assistant"
        ["content"]=>
        string(564) "Bien sûr! OpenAI est une organisation de recherche spécialisée dans l'intelligence artificielle. Ils travaillent sur des projets de pointe en matière de traitement du langage naturel, d'apprentissage par renforcement, d'analyse de données et plus encore. Ils ont également conçu des outils de développement d'IA tels que TensorFlow et Gym. OpenAI est considéré comme l'un des principaux acteurs mondiaux de l'IA et collabore avec des partenaires industriels et universitaires pour faire avancer la recherche dans ce domaine. J'espère que cela vous aide!"
      }
      ["finish_reason"]=>
      string(4) "stop"
      ["index"]=>
      int(0)
    }
  }
}
```

[Back to top](#installation)


## Create stream chat completion
> **Creates a model response for the given chat conversation with stream.**

### Request (Create stream chat completion)
```php
use MounirRquiba\OpenAi\Services\Completions;

$chat = (new Chat())
    ->create([
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            [
                'role' => 'user',
                'content' => "Je ne comprends pas OpenAi tu peux m'aider ?"
            ]
        ],
        'stream' => true
    ]);

foreach ($chat->getResponse() as $value) {
    if (isset($value['choices'][0]['delta']['content'])) {
        echo $value['choices'][0]['delta']['content'];
    }
}

echo PHP_EOL;
```
### Response (Create stream chat completion)
```text
Bien sûr ! OpenAI est une entreprise de recherche en intelligence artificielle fondée en 2015 par plusieurs personnalités du monde technologique, notamment Elon Musk. Son objectif est de développer une IA avancée qui peut résoudre des problèmes complexes et améliorer de nombreux domaines, tels que la médecine, le transport, la finance et l'éducation. OpenAI est également connu pour développer des modèles de langage tels que GPT-3, qui est capable de produire des textes semblables à ceux écrits par des humains. En résumé, OpenAI est une entreprise qui travaille pour construire une intelligence artificielle avancée pour aider à résoudre des problèmes et améliorer notre monde.
```


[Back to top](#installation)


## Edits
> **Creates a new edit for the provided input, instruction, and parameters.**

### Request (Edits)
```php
use MounirRquiba\OpenAi\Services\Edits;

$edits = (new Edits())
    ->create([
        'model' => "text-davinci-edit-001",
        'input' => "Coment va le marché français de l'or ?\n\nDans quels pays y a-t-il le plus d'or ?\n",
        'instruction' => "Corriger les fautes\nDonner la liste des 10 pays ou il y a le plus d'or dans l'ordre décroissant",
        'temperature' => 0.7,
        'top_p' => 1,
    ]);

var_dump($edits->getResponse());
```
### Response (Edits)
```php
array(4) {
  ["object"]=>
  string(4) "edit"
  ["created"]=>
  int(1684343572)
  ["choices"]=>
  array(1) {
    [0]=>
    array(2) {
      ["text"]=>
      string(243) "Comment va le marché français de l'or ?

Dans quels pays y a-t-il le plus d'or ?

La liste des dix pays où il y a le plus d'or dans l'ordre décroissant :
Etats-Unis, Allemagne, Italie, France, Chine, Russie, Suisse, Japon, Inde, Pays-Bas.
"
      ["index"]=>
      int(0)
    }
  }
  ["usage"]=>
  array(3) {
    ["prompt_tokens"]=>
    int(80)
    ["completion_tokens"]=>
    int(119)
    ["total_tokens"]=>
    int(199)
  }
}
```

[Back to top](#installation)

## Images
> **Given a prompt and/or an input image, the model will generate a new image.**

## Images Generations
*Creates an image given a prompt.*
### Request (Images Generations)
```php
use MounirRquiba\OpenAi\Services\ImagesGenerations;

$imagesGenerations = (new ImagesGenerations())
    ->create([
        'prompt' =>'un vélo dinosaur, qui roule sur la tour effel',
        'n' => 2
    ]);

var_dump($imagesGenerations->getResponse());
```
### Response (Images Generations)
```php
array(2) {
  ["created"]=>
  int(1684458446)
  ["data"]=>
  array(2) {
    [0]=>
    array(1) {
      ["url"]=>
      string(472) "https://oaidalleapiprodscus.blob.core.windows.net/private/org-bYCY6SRU0TjFN5GBGPo6sKXi/user-WdcLONQGarCf7HGjXdwRUzg7/img-AhrLfzRIHfTLt2sEBEgOjNjQ.png?st=2023-05-19T00%3A07%3A26Z&se=2023-05-19T02%3A07%3A26Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2023-05-18T20%3A54%3A11Z&ske=2023-05-19T20%3A54%3A11Z&sks=b&skv=2021-08-06&sig=JuabiiK7cjQJxCdcv3A8jHaKCi6HAsHNBDJFCAaaKqQ%3D"
    }
    [1]=>
    array(1) {
      ["url"]=>
      string(472) "https://oaidalleapiprodscus.blob.core.windows.net/private/org-bYCY6SRU0TjFN5GBGPo6sKXi/user-WdcLONQGarCf7HGjXdwRUzg7/img-9XkQyXSada8cpZ1ptVTHybPc.png?st=2023-05-19T00%3A07%3A26Z&se=2023-05-19T02%3A07%3A26Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2023-05-18T20%3A54%3A11Z&ske=2023-05-19T20%3A54%3A11Z&sks=b&skv=2021-08-06&sig=QkD9Aa7dRdFLPwXU9Dl0MGC6iwbjCVUZ4fPN49X1mF8%3D"
    }
  }
}
```

[Back to top](#installation)


## Images Edits
*Creates an edited or extended image given an original image and a prompt.*
### Request (Images Edits)
```php
use MounirRquiba\OpenAi\Services\ImagesEdits;

$imagesEdits = (new ImagesEdits())
    ->create([
        'image' => __DIR__ . '/assets/image_edit_original.png',
        'mask' => __DIR__ . '/assets/image_edit_mask.png',
        'prompt' => 'A cute baby sea otter wearing a beret',
        'n' => 2
    ]);

var_dump($imagesEdits->getResponse());
```
### Response (Images Edits)
```php
array(2) {
  ["created"]=>
  int(1684458473)
  ["data"]=>
  array(2) {
    [0]=>
    array(1) {
      ["url"]=>
      string(472) "https://oaidalleapiprodscus.blob.core.windows.net/private/org-bYCY6SRU0TjFN5GBGPo6sKXi/user-WdcLONQGarCf7HGjXdwRUzg7/img-HDlBhDfbk3QIXEdpEWSgXVYa.png?st=2023-05-19T00%3A07%3A53Z&se=2023-05-19T02%3A07%3A53Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2023-05-18T20%3A52%3A38Z&ske=2023-05-19T20%3A52%3A38Z&sks=b&skv=2021-08-06&sig=aOAibUdcLhr3sjuu8L1I2vqkNBghaQOl6KvAD9BbpUY%3D"
    }
    [1]=>
    array(1) {
      ["url"]=>
      string(474) "https://oaidalleapiprodscus.blob.core.windows.net/private/org-bYCY6SRU0TjFN5GBGPo6sKXi/user-WdcLONQGarCf7HGjXdwRUzg7/img-2tWHWSaD11jizwLd8CsnynPm.png?st=2023-05-19T00%3A07%3A53Z&se=2023-05-19T02%3A07%3A53Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2023-05-18T20%3A52%3A38Z&ske=2023-05-19T20%3A52%3A38Z&sks=b&skv=2021-08-06&sig=CCI2VjThND9%2BJwW7MqJiBITR1PYVs7bk6QiAlDeKjvk%3D"
    }
  }
}
```

[Back to top](#installation)

## Images Variations
*Creates a variation of a given image.*

### Request (Images Variations)
```php
use MounirRquiba\OpenAi\Services\ImagesVariations;

$imagesVariations = (new ImagesVariations())
    ->create([
        'image' => __DIR__ . '/assets/image_edit_original.png',
        'n' => 2
    ]);

var_dump($imagesVariations->getResponse());
```
### Response (Images Variations)
```php
array(2) {
  ["created"]=>
  int(1684458458)
  ["data"]=>
  array(2) {
    [0]=>
    array(1) {
      ["url"]=>
      string(478) "https://oaidalleapiprodscus.blob.core.windows.net/private/org-bYCY6SRU0TjFN5GBGPo6sKXi/user-WdcLONQGarCf7HGjXdwRUzg7/img-f0xkAAbGa16cz39LnBAmxhan.png?st=2023-05-19T00%3A07%3A38Z&se=2023-05-19T02%3A07%3A38Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2023-05-18T20%3A55%3A27Z&ske=2023-05-19T20%3A55%3A27Z&sks=b&skv=2021-08-06&sig=SAeBm6Z/33%2B/P5fSW5WciH970tZif%2BOP6hxEZl9c%2BrM%3D"
    }
    [1]=>
    array(1) {
      ["url"]=>
      string(472) "https://oaidalleapiprodscus.blob.core.windows.net/private/org-bYCY6SRU0TjFN5GBGPo6sKXi/user-WdcLONQGarCf7HGjXdwRUzg7/img-yZi1zVNgq7jd52nzEfVIGrud.png?st=2023-05-19T00%3A07%3A38Z&se=2023-05-19T02%3A07%3A38Z&sp=r&sv=2021-08-06&sr=b&rscd=inline&rsct=image/png&skoid=6aaadede-4fb3-4698-a8f6-684d7786b067&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2023-05-18T20%3A55%3A27Z&ske=2023-05-19T20%3A55%3A27Z&sks=b&skv=2021-08-06&sig=sAK0yu3GkiRfek2F/YH0wL0z3KpqRm8Hhj7eyWW3Si8%3D"
    }
  }
}
```


[Back to top](#installation)

## Embeddings
> **Get a vector representation of a given input that can be easily consumed by machine learning models and algorithms.**
*Creates an embedding vector representing the input text.*


### Request (Embeddings)
```php
use MounirRquiba\OpenAi\Services\Embeddings;

$embeddings = (new Embeddings())
    ->create([
        'model' => 'text-embedding-ada-002',
        'input' => "Le paim etait bon et le boulanger..."
    ]);

var_dump($embeddings->getResponse());
```
### Response (Embeddings)
```php
array(4) {
  ["object"]=>
  string(4) "list"
  ["data"]=>
  array(1) {
    [0]=>
    array(3) {
      ["object"]=>
      string(9) "embedding"
      ["index"]=>
      int(0)
      ["embedding"]=>
      array(1536) {
        [0]=>
        float(0.0046150074)
        ...
        [1535]=>
        float(0.010371089)
      }
    }
  }
  ["model"]=>
  string(25) "text-embedding-ada-002-v2"
  ["usage"]=>
  array(2) {
    ["prompt_tokens"]=>
    int(12)
    ["total_tokens"]=>
    int(12)
  }
}
```


[Back to top](#installation)

## Audio
> **Creates a new edit for the provided input, instruction, and parameters.**

## Audio Transcriptions
*Transcribes audio into the input language.*

### Request (Audio Transcriptions)
```php
use MounirRquiba\OpenAi\Services\AudioTranscriptions;

$audioTranscriptions = (new AudioTranscriptions())
    ->create([
        'file' => __DIR__ . '/assets/multilingual.mp3',
        'model' => 'whisper-1',
        'response_format' => 'json'
    ]);

var_dump($audioTranscriptions->getResponse());
```
### Response (Audio Transcriptions)
```php
array(1) {
  ["text"]=>
  string(747) "Whisper est un système de reconnaissance automatique de la parole entraîné sur 680 000 heures de données multilingues et multitâches récoltées sur Internet. Nous établissons que l'utilisation de données d'un tel nombre et d'une telle diversité est la raison pour laquelle votre système est à même de comprendre de nombreux accents en dépit de bruit de fond, de comprendre un vocabulaire technique et de réussir la traduction depuis diverses langues en anglais. Nous distribuons en tant que logiciel libre le code source pour nos modèles et pour l'inférence afin que ceux-ci puissent servir comme un point de départ pour construire des applications utiles et pour aider à faire progresser la recherche en traitement de la parole."
}
```


[Back to top](#installation)

## Audio Translations
*Translates audio into into English.*

### Request (Audio Translations)
```php
use MounirRquiba\OpenAi\Services\AudioTranslations;

$audioTranslations = (new AudioTranslations())
    ->create([
        'file' => __DIR__ . '/assets/multilingual.mp3',
        'model' => 'whisper-1',
        'response_format' => 'json'
    ]);

var_dump($audioTranslations->getResponse());
```
### Response (Audio Transcriptions)
```php
array(1) {
  ["text"]=>
  string(637) "Whisper is an automatic recognition system of speech, trained on 680,000 hours of multilingual and multitask data, collected on the Internet. We establish that the use of such a large number of data is such a diversity, and the reason why our system is able to understand many accents, despite background noise, to understand technical vocabulary, and to succeed in translation from various languages into English. We distribute the source code for our models and for the inference as a free software, so that they can serve as a starting point for building useful applications, and to help to progress the research in speech processing."
}
```


[Back to top](#installation)

## Files
> **Files are used to upload documents that can be used with features like Fine-tuning.**

## List files
Returns a list of files that belong to the user's organization.

### Request (List files)
```php
use MounirRquiba\OpenAi\Services\Files;

$files = (new Files())
    ->create();

var_dump($files->getResponse());
```
### Response (List files)
```php
array(2) {
  ["object"]=>
  string(4) "list"
  ["data"]=>
  array(1) {
    [0]=>
    array(8) {
      ["object"]=>
      string(4) "file"
      ["id"]=>
      string(29) "file-CaNvI5ua4WrAanl5kfDfE1gD"
      ["purpose"]=>
      string(9) "fine-tune"
      ["filename"]=>
      string(23) "FineTuningSample1.jsonl"
      ["bytes"]=>
      int(9099)
      ["created_at"]=>
      int(1684186918)
      ["status"]=>
      string(9) "processed"
      ["status_details"]=>
      NULL
    }
  }
}
```


[Back to top](#installation)

## Upload file
Upload a file that contains document(s) to be used across various endpoints/features. Currently, the size of all the files uploaded by one organization can be up to 1 GB. Please contact us if you need to increase the storage limit.

### Request (Upload file)
```php
use MounirRquiba\OpenAi\Services\FileUpload;

$fileUpload = (new FileUpload())
    ->create([
        'file' => __DIR__ . '/assets/FineTuningSample1.jsonl',
        'purpose' => 'fine-tune',
    ]);

var_dump($fileUpload->getResponse());
```
### Response (Upload file)
```php
array(8) {
  ["object"]=>
  string(4) "file"
  ["id"]=>
  string(29) "file-UIsWSH2zoQ58stVPIFlapM4c"
  ["purpose"]=>
  string(9) "fine-tune"
  ["filename"]=>
  string(23) "FineTuningSample1.jsonl"
  ["bytes"]=>
  int(9099)
  ["created_at"]=>
  int(1684458834)
  ["status"]=>
  string(8) "uploaded"
  ["status_details"]=>
  NULL
}
```

## Delete file
Delete a file.

### Request (Delete file)
```php
use MounirRquiba\OpenAi\Services\FileDelete;

$fileDelete = (new FileDelete('file-UIsWSH2zoQ58stVPIFlapM4c'))
    ->create();

// or

$fileDelete = (new FileDelete())
    ->setFile('file-UIsWSH2zoQ58stVPIFlapM4c')
    ->create();

var_dump($fileDelete->getResponse());
```
### Response (Delete file)
```php
array(3) {
  ["object"]=>
  string(4) "file"
  ["id"]=>
  string(29) "file-UIsWSH2zoQ58stVPIFlapM4c"
  ["deleted"]=>
  bool(true)
}
```


[Back to top](#installation)

## Retrieve file
Returns information about a specific file.

### Request (Retrieve file)
```php
use MounirRquiba\OpenAi\Services\File;

$file = (new File('file-DILkImh8E8Gl3PEGY1kD95BA'))
    ->create();

// or

$file = (new FineTune())
    ->setFile('file-DILkImh8E8Gl3PEGY1kD95BA')
    ->create();

var_dump($file->getResponse());
```
### Response (Retrieve file)
```php
array(8) {
  ["object"]=>
  string(4) "file"
  ["id"]=>
  string(29) "file-DILkImh8E8Gl3PEGY1kD95BA"
  ["purpose"]=>
  string(9) "fine-tune"
  ["filename"]=>
  string(23) "FineTuningSample1.jsonl"
  ["bytes"]=>
  int(9099)
  ["created_at"]=>
  int(1684148376)
  ["status"]=>
  string(9) "processed"
  ["status_details"]=>
  NULL
}
```

[Back to top](#installation)


## Retrieve file content
Returns the contents of the specified file

### Request (Retrieve file content)
```php
use MounirRquiba\OpenAi\Services\FileContent;

$fileContent = (new FileContent('file-DILkImh8E8Gl3PEGY1kD95BA'))
    ->create();

// or

$fileContent = (new FineTune())
    ->setFile('file-DILkImh8E8Gl3PEGY1kD95BA')
    ->create();

var_dump($fileContent->getResponse());
```
### Response (Retrieve file content)
```text
string(9099) "{"prompt":"Overjoyed with the new iPhone! ->", "completion":" positive"}
{"prompt":"@lakers disappoint for a third straight night https://t.co/38EFe43 ->", "completion":" negative"}
{"prompt":"Overjoyed with the new iPhone! ->", "completion":" positive"}
{"prompt":"@lakers disappoint for a third straight night https://t.co/38EFe43 ->", "completion":" negative"}
{"prompt":"Overjoyed with the new iPhone! ->", "completion":" positive"}
{"prompt":"@lakers disappoint for a third straight night https://t.co/38EFe43 ->", "completion":" negative"}
{"prompt":"Overjoyed with the new iPhone! ->", "completion":" positive"}
{"prompt":"@lakers disappoint for a third straight night https://t.co/38EFe43 ->", "completion":" negative"}"
```


[Back to top](#installation)


# __Fine-tune__
> **Manage fine-tuning jobs to tailor a model to your specific training data.**

## Create fine-tune
Creates a job that fine-tunes a specified model from a given dataset.
Response includes details of the enqueued job including job status and the name of the fine-tuned models once complete.

### Request (Create fine-tune)
```php
use MounirRquiba\OpenAi\Services\FineTuneCreate;

$fineTuneCreate = (new FineTuneCreate())
    ->create([
        'training_file' => 'file-rr52uDaNMcspoOZ4bAu3wbOS',
        'model' => 'curie'
    ]);

var_dump($fineTuneCreate->getResponse());
```
### Response (Create fine-tune)
```php
array(13) {
  ["object"]=>
  string(9) "fine-tune"
  ["id"]=>
  string(27) "ft-I8JdQV6SbzTrhpkfCBiSXRYO"
  ["hyperparams"]=>
  array(4) {
    ["n_epochs"]=>
    int(4)
    ["batch_size"]=>
    NULL
    ["prompt_loss_weight"]=>
    float(0.01)
    ["learning_rate_multiplier"]=>
    NULL
  }
  ["organization_id"]=>
  string(28) "org-bYCY6SRU0TjFN5GBGPo6sKXi"
  ["model"]=>
  string(5) "curie"
  ["training_files"]=>
  array(1) {
    [0]=>
    array(8) {
      ["object"]=>
      string(4) "file"
      ["id"]=>
      string(29) "file-rr52uDaNMcspoOZ4bAu3wbOS"
      ["purpose"]=>
      string(9) "fine-tune"
      ["filename"]=>
      string(23) "FineTuningSample1.jsonl"
      ["bytes"]=>
      int(9099)
      ["created_at"]=>
      int(1684187069)
      ["status"]=>
      string(9) "processed"
      ["status_details"]=>
      NULL
    }
  }
  ["validation_files"]=>
  array(0) {
  }
  ["result_files"]=>
  array(0) {
  }
  ["created_at"]=>
  int(1684641945)
  ["updated_at"]=>
  int(1684641945)
  ["status"]=>
  string(7) "pending"
  ["fine_tuned_model"]=>
  NULL
  ["events"]=>
  array(1) {
    [0]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(46) "Created fine-tune: ft-I8JdQV6SbzTrhpkfCBiSXRYO"
      ["created_at"]=>
      int(1684641945)
    }
  }
}
```

[Back to top](#installation)


## List fine-tunes
List your organization's fine-tuning jobs

### Request (List fine-tunes)
```php
use MounirRquiba\OpenAi\Services\FineTunes;

$fineTunes = (new FineTunes())
    ->create();

var_dump($fineTunes->getResponse());
```
### Response (List fine-tunes)
```php
array(2) {
  ["object"]=>
  string(4) "list"
  ["data"]=>
  array(1) {
    [0]=>
    array(12) {
      ["object"]=>
      string(9) "fine-tune"
      ["id"]=>
      string(27) "ft-kp36A6V0yCxixhdNOd1khEH1"
      ["hyperparams"]=>
      array(4) {
        ["n_epochs"]=>
        int(4)
        ["batch_size"]=>
        int(1)
        ["prompt_loss_weight"]=>
        float(0.01)
        ["learning_rate_multiplier"]=>
        float(0.1)
      }
      ["organization_id"]=>
      string(28) "org-bYCY6SRU0TjFN5GBGPo6sKXi"
      ["model"]=>
      string(5) "curie"
      ["training_files"]=>
      array(1) {
        [0]=>
        array(8) {
          ["object"]=>
          string(4) "file"
          ["id"]=>
          string(29) "file-DILkImh8E8Gl3PEGY1kD95BA"
          ["purpose"]=>
          string(9) "fine-tune"
          ["filename"]=>
          string(23) "FineTuningSample1.jsonl"
          ["bytes"]=>
          int(9099)
          ["created_at"]=>
          int(1684148376)
          ["status"]=>
          string(9) "processed"
          ["status_details"]=>
          NULL
        }
      }
      ["validation_files"]=>
      array(0) {
      }
      ["result_files"]=>
      array(0) {
      }
      ["created_at"]=>
      int(1684186072)
      ["updated_at"]=>
      int(1684186226)
      ["status"]=>
      string(9) "cancelled"
      ["fine_tuned_model"]=>
      NULL
    }
  }
}
```

[Back to top](#installation)


## Retrieve fine-tune
Gets info about the fine-tune job.

### Request (Retrieve fine-tune)
```php
use MounirRquiba\OpenAi\Services\FineTune;

$fineTune = (new FineTune('ft-APj3KgkNKa8vjrt67WyFf1oU'))
    ->create();

// or

$fineTune = (new FineTune())
    ->setFineTune('ft-APj3KgkNKa8vjrt67WyFf1oU')
    ->create();

var_dump($fineTune->getResponse());
```
### Response (Retrieve fine-tune)
```php
array(13) {
  ["object"]=>
  string(9) "fine-tune"
  ["id"]=>
  string(27) "ft-APj3KgkNKa8vjrt67WyFf1oU"
  ["hyperparams"]=>
  array(4) {
    ["n_epochs"]=>
    int(4)
    ["batch_size"]=>
    int(1)
    ["prompt_loss_weight"]=>
    float(0.01)
    ["learning_rate_multiplier"]=>
    float(0.1)
  }
  ["organization_id"]=>
  string(28) "org-bYCY6SRU0TjFN5GBGPo6sKXi"
  ["model"]=>
  string(5) "curie"
  ["training_files"]=>
  array(1) {
    [0]=>
    array(8) {
      ["object"]=>
      string(4) "file"
      ["id"]=>
      string(29) "file-rr52uDaNMcspoOZ4bAu3wbOS"
      ["purpose"]=>
      string(9) "fine-tune"
      ["filename"]=>
      string(23) "FineTuningSample1.jsonl"
      ["bytes"]=>
      int(9099)
      ["created_at"]=>
      int(1684187069)
      ["status"]=>
      string(9) "processed"
      ["status_details"]=>
      NULL
    }
  }
  ["validation_files"]=>
  array(0) {
  }
  ["result_files"]=>
  array(1) {
    [0]=>
    array(8) {
      ["object"]=>
      string(4) "file"
      ["id"]=>
      string(29) "file-wLlZV8ebowFsXumRBkQ9LCQE"
      ["purpose"]=>
      string(17) "fine-tune-results"
      ["filename"]=>
      string(20) "compiled_results.csv"
      ["bytes"]=>
      int(17141)
      ["created_at"]=>
      int(1684192045)
      ["status"]=>
      string(9) "processed"
      ["status_details"]=>
      NULL
    }
  }
  ["created_at"]=>
  int(1684187099)
  ["updated_at"]=>
  int(1684192045)
  ["status"]=>
  string(9) "succeeded"
  ["fine_tuned_model"]=>
  string(37) "curie:ft-personal-2023-05-15-23-07-24"
  ["events"]=>
  array(13) {
    [0]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(46) "Created fine-tune: ft-APj3KgkNKa8vjrt67WyFf1oU"
      ["created_at"]=>
      int(1684187099)
    }
    [1]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(21) "Fine-tune costs $0.02"
      ["created_at"]=>
      int(1684191198)
    }
    [2]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(35) "Fine-tune enqueued. Queue number: 2"
      ["created_at"]=>
      int(1684191198)
    }
    [3]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(42) "Fine-tune is in the queue. Queue number: 1"
      ["created_at"]=>
      int(1684191679)
    }
    [4]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(42) "Fine-tune is in the queue. Queue number: 0"
      ["created_at"]=>
      int(1684191796)
    }
    [5]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(17) "Fine-tune started"
      ["created_at"]=>
      int(1684191892)
    }
    [6]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(19) "Completed epoch 1/4"
      ["created_at"]=>
      int(1684191970)
    }
    [7]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(19) "Completed epoch 2/4"
      ["created_at"]=>
      int(1684191987)
    }
    [8]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(19) "Completed epoch 3/4"
      ["created_at"]=>
      int(1684192005)
    }
    [9]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(19) "Completed epoch 4/4"
      ["created_at"]=>
      int(1684192022)
    }
    [10]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(53) "Uploaded model: curie:ft-personal-2023-05-15-23-07-24"
      ["created_at"]=>
      int(1684192044)
    }
    [11]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(51) "Uploaded result file: file-wLlZV8ebowFsXumRBkQ9LCQE"
      ["created_at"]=>
      int(1684192045)
    }
    [12]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(19) "Fine-tune succeeded"
      ["created_at"]=>
      int(1684192045)
    }
  }
}
```

[Back to top](#installation)


## Cancel fine-tune
Immediately cancel a fine-tune job.

### Request (Cancel fine-tune)
```php
use MounirRquiba\OpenAi\Services\FineTuneCancel;

$fineTuneCancel = (new FineTuneCancel('ft-kp36A6V0yCxixhdNOd1khEH1'))
    ->create();

// or

$fineTuneCancel = (new FineTuneCancel())
    ->setFineTune('ft-APj3KgkNKa8vjrt67WyFf1oU')
    ->create();

var_dump($fineTuneCancel->getResponse());
```
### Response (Cancel fine-tune)
```php
array(13) {
  ["object"]=>
  string(9) "fine-tune"
  ["id"]=>
  string(27) "ft-SyOpGKgquTE2wYBEMI2X3pJl"
  ["hyperparams"]=>
  array(4) {
    ["n_epochs"]=>
    int(4)
    ["batch_size"]=>
    NULL
    ["prompt_loss_weight"]=>
    float(0.01)
    ["learning_rate_multiplier"]=>
    NULL
  }
  ["organization_id"]=>
  string(28) "org-bYCY6SRU0TjFN5GBGPo6sKXi"
  ["model"]=>
  string(5) "curie"
  ["training_files"]=>
  array(1) {
    [0]=>
    array(8) {
      ["object"]=>
      string(4) "file"
      ["id"]=>
      string(29) "file-rr52uDaNMcspoOZ4bAu3wbOS"
      ["purpose"]=>
      string(9) "fine-tune"
      ["filename"]=>
      string(23) "FineTuningSample1.jsonl"
      ["bytes"]=>
      int(9099)
      ["created_at"]=>
      int(1684187069)
      ["status"]=>
      string(9) "processed"
      ["status_details"]=>
      NULL
    }
  }
  ["validation_files"]=>
  array(0) {
  }
  ["result_files"]=>
  array(0) {
  }
  ["created_at"]=>
  int(1684642234)
  ["updated_at"]=>
  int(1684642248)
  ["status"]=>
  string(9) "cancelled"
  ["fine_tuned_model"]=>
  NULL
  ["events"]=>
  array(2) {
    [0]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(46) "Created fine-tune: ft-SyOpGKgquTE2wYBEMI2X3pJl"
      ["created_at"]=>
      int(1684642234)
    }
    [1]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(19) "Fine-tune cancelled"
      ["created_at"]=>
      int(1684642248)
    }
  }
}
```

[Back to top](#installation)


## List fine-tune events
Get fine-grained status updates for a fine-tune job.

### Request (List fine-tune events)
```php
use MounirRquiba\OpenAi\Services\FineTuneEvents;

$fineTuneEvents = (new FineTuneEvents('ft-APj3KgkNKa8vjrt67WyFf1oU'))
    ->create();

// or

$fineTuneEvents = (new FineTuneEvents())
    ->setFineTune('ft-APj3KgkNKa8vjrt67WyFf1oU')
    ->create();

var_dump($fineTuneEvents->getResponse());
```
### Response (List fine-tune events)
```php
array(2) {
  ["object"]=>
  string(4) "list"
  ["data"]=>
  array(11) {
    [0]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(46) "Created fine-tune: ft-5oS3cDvnkPJylOVcqUXUaMcl"
      ["created_at"]=>
      int(1684640314)
    }
    [1]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(21) "Fine-tune costs $0.02"
      ["created_at"]=>
      int(1684640417)
    }
    [2]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(35) "Fine-tune enqueued. Queue number: 0"
      ["created_at"]=>
      int(1684640418)
    }
    [3]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(17) "Fine-tune started"
      ["created_at"]=>
      int(1684641626)
    }
    [4]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(19) "Completed epoch 1/4"
      ["created_at"]=>
      int(1684641705)
    }
    [5]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(19) "Completed epoch 2/4"
      ["created_at"]=>
      int(1684641723)
    }
    [6]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(19) "Completed epoch 3/4"
      ["created_at"]=>
      int(1684641741)
    }
    [7]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(19) "Completed epoch 4/4"
      ["created_at"]=>
      int(1684641759)
    }
    [8]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(53) "Uploaded model: curie:ft-personal-2023-05-21-04-02-58"
      ["created_at"]=>
      int(1684641778)
    }
    [9]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(51) "Uploaded result file: file-NxV8qq6jYEOStE1aT3OGiF7D"
      ["created_at"]=>
      int(1684641779)
    }
    [10]=>
    array(4) {
      ["object"]=>
      string(15) "fine-tune-event"
      ["level"]=>
      string(4) "info"
      ["message"]=>
      string(19) "Fine-tune succeeded"
      ["created_at"]=>
      int(1684641779)
    }
  }
}
```

[Back to top](#installation)


## Delete fine-tune model
Delete a fine-tuned model. You must have the Owner role in your organization.

### Request (Delete fine-tune model)
```php
use MounirRquiba\OpenAi\Services\FineTuneDelete;

$fineTuneDelete = (new FineTuneDelete())
    ->setModel('curie:ft-personal-2023-05-15-22-35-28')
    ->create();

// or

$fineTuneDelete = (new FineTuneDelete('curie:ft-personal-2023-05-15-22-35-28'))
   ->create();

var_dump($fineTuneDelete->getResponse());
```
### Response (Delete fine-tune model)
```php
array(3) {
  ["id"]=>
  string(37) "curie:ft-personal-2023-05-21-04-02-58"
  ["object"]=>
  string(5) "model"
  ["deleted"]=>
  bool(true)
}
```

[Back to top](#installation)


## Moderations
> **Given a input text, outputs if the model classifies it as violating OpenAI's content policy.**
Classifies if text violates OpenAI's Content Policy

### Request (Moderations)
```php
use MounirRquiba\OpenAi\Services\Moderations;

$moderations = (new Moderations())
    ->create([
        'input' => ['la vie est belle', "il va le tuer"]
    ]);

var_dump($moderations->getResponse());
```
### Response (Moderations)
```php
array(3) {
  ["id"]=>
  string(34) "modr-7HjGIr3itczNK6ypzfBtPgoJOi849"
  ["model"]=>
  string(19) "text-moderation-004"
  ["results"]=>
  array(2) {
    [0]=>
    array(3) {
      ["flagged"]=>
      bool(false)
      ["categories"]=>
      array(7) {
        ["sexual"]=>
        bool(false)
        ["hate"]=>
        bool(false)
        ["violence"]=>
        bool(false)
        ["self-harm"]=>
        bool(false)
        ["sexual/minors"]=>
        bool(false)
        ["hate/threatening"]=>
        bool(false)
        ["violence/graphic"]=>
        bool(false)
      }
      ["category_scores"]=>
      array(7) {
        ["sexual"]=>
        float(9.854588E-5)
        ["hate"]=>
        float(1.8268647E-5)
        ["violence"]=>
        float(8.085035E-7)
        ["self-harm"]=>
        float(2.9571123E-7)
        ["sexual/minors"]=>
        float(1.06537414E-7)
        ["hate/threatening"]=>
        float(2.2165905E-9)
        ["violence/graphic"]=>
        float(1.0610097E-8)
      }
    }
    [1]=>
    array(3) {
      ["flagged"]=>
      bool(true)
      ["categories"]=>
      array(7) {
        ["sexual"]=>
        bool(false)
        ["hate"]=>
        bool(false)
        ["violence"]=>
        bool(true)
        ["self-harm"]=>
        bool(false)
        ["sexual/minors"]=>
        bool(false)
        ["hate/threatening"]=>
        bool(false)
        ["violence/graphic"]=>
        bool(false)
      }
      ["category_scores"]=>
      array(7) {
        ["sexual"]=>
        float(0.0005990547)
        ["hate"]=>
        float(0.0016128556)
        ["violence"]=>
        float(0.79453164)
        ["self-harm"]=>
        float(4.4971974E-5)
        ["sexual/minors"]=>
        float(6.0204526E-5)
        ["hate/threatening"]=>
        float(2.81084E-5)
        ["violence/graphic"]=>
        float(2.5406101E-5)
      }
    }
  }
}
```

[Back to top](#installation)


## Exceptions
The provided code demonstrates a try-catch block for handling exceptions.

```php
try {
    $audioTranslations = (new AudioTranslations())
        ->create([
            'file' => __DIR__ . '/assets/multilingual.mp3',
            'model' => 'whisper-1',
            'response_format' => 'json'
        ]);
        var_dump($audioTranslations->getResponse());
}
catch(FileNotFoundException $e) {
    var_dump($e->getMessage());
}
catch(BadResponseException $e) {
    var_dump($e->getMessage());
}
catch(InvalidParameterException $e) {
    var_dump($e->getMessage());
}
catch(RequiredParameterException $e) {
    var_dump($e->getMessage());
}
catch(\Exception $e) {
    var_dump($e->getMessage());
}


if (isset($audioTranslations)) {
    var_dump($audioTranslations->getResponse());
}
```
List of exceptions:
* __FileNotFoundException__: It is used to handle exceptions related to files that are not found or inaccessible.
* __BadResponseException__: It is used to handle exceptions related to bad or unexpected responses.
* __InvalidParameterException__: It is used to handle exceptions related to invalid parameters passed.
* __RequiredParameterException__: It is used to handle exceptions related to required parameters.


[Back to top](#installation)

## Tests
```shell
git clone git@github.com:mounirrquiba/openai-php-client.git

cd ./openai-php-client

composer install

composer composer run-script test
```

[Back to top](#installation)

## License
The MIT License (MIT)

Copyright (c) Mounir R'Quiba | https://www.linkedin.com/in/mounir-r-quiba-14aa84ba/

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
