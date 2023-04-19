<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Templates\Prompts;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Template\Replace;
use Ssc\Btlr\Cht\Message\Logs\WriteLog;

class Template
{
    public function __construct(
        private ReadFile $readFile,
        private Replace $replace,
        private WriteLog $writeLog,
    ) {
    }

    public function replace(
        array $thoseParameters,
        array $forType,
        array $withConfig,
    ): string {
        $template = $this->readFile->in(
            "{$withConfig['prompt_templates_filename']}/{$forType['name']}.txt",
        );
        $prompt = $this->replace->in($template, $thoseParameters);
        $this->writeLog->for([
            'entry' => $prompt,
        ], $forType, $withConfig);

        return $prompt;
    }
}
