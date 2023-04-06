<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Augment\UsingLlm;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Template\Replace;
use Ssc\Btlr\Cht\Augment\UsingLlm\Augment\GetLastMessages;
use Ssc\Btlr\Cht\Augment\UsingLlm\Log\Source;

class Augment
{
    public function __construct(
        private GetLastMessages $getLastMessages,
        private Log $log,
        private ReadFile $readFile,
        private Replace $replace,
    ) {
    }

    public function the(
        string $userPrompt,
        array $withConfig,
    ): string {
        $lastMessages = $this->getLastMessages->from($withConfig['last_messages_filename']);
        $template = $this->readFile->in($withConfig['augmented_prompt_template_filename']);

        $augmentedPrompt = $this->replace->in($template, thoseParameters: [
            'last_messages' => $lastMessages,
            'user_prompt' => $userPrompt,
        ]);
        $this->log->entry($augmentedPrompt, $withConfig, Source::AUGMENTED_PROMPT);

        return $augmentedPrompt;
    }
}
