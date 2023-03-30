<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Augment\UsingLlm;

use Ssc\Btlr\Cht\Augment\UsingLlm\Model\Engine;

class Model
{
    public function __construct(
        private Engine $engine,
    ) {
    }

    public function switch(Engine $engine): void
    {
        $this->engine = $engine;
    }

    public function complete(string $prompt): string
    {
        return $this->engine->complete($prompt);
    }
}
