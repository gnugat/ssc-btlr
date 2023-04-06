<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Augment\UsingLlm\Model\Engine;

use Ssc\Btlr\App\Stdio;
use Ssc\Btlr\App\Stdio\Write\WithStyle;
use Ssc\Btlr\Cht\Augment\UsingLlm\Model\Engine;

class Cli implements Engine
{
    public function __construct(
        private Stdio $stdio,
    ) {
    }

    public function complete(string $prompt): string
    {
        $this->stdio->write(
            'ℹ️  Manual Mode enabled',
            WithStyle::AS_INSTRUCTION,
        );
        $this->stdio->write(
            'Please copy/paste the following prompt to your favorite LLM:',
            WithStyle::AS_INSTRUCTION,
        );
        $this->stdio->write("{$prompt}\n");
        $this->stdio->write(
            "Then copy/paste the LLMs' completion here",
            WithStyle::AS_INSTRUCTION,
        );
        $this->stdio->write(Stdio::EMPTY_LINE);

        return $this->stdio->ask("Provide the completion:\n");
    }
}
