<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Augment\UsingLlm;

use Ssc\Btlr\Cht\Augment\UsingLlm\Log\Source;
use Ssc\Btlr\Framework\Filesystem\ReadFile;
use Ssc\Btlr\Framework\Template\Replace;

class Augment
{
    public function __construct(
        private Log $log,
        private ReadFile $readFile,
        private Replace $replace,
    ) {
    }

    public function the(
        string $userPrompt,
        array $withConfig,
    ): string {
        $template = $this->readFile->in($withConfig['augmented_prompt_template_filename']);

        $augmentedPrompt = $this->replace->in($template, thoseParameters: [
            'user_prompt' => $userPrompt,
        ]);
        $this->log->entry($augmentedPrompt, $withConfig, Source::AUGMENTED_PROMPT);

        return $augmentedPrompt;
    }
}
