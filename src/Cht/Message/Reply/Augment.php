<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Reply;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Template\Replace;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs;
use Ssc\Btlr\Cht\Message\Reply\Augment\FormatAsConversation;

class Augment
{
    public function __construct(
        private FormatAsConversation $formatAsConversation,
        private ListLogs $listLogs,
        private ReadFile $readFile,
        private Replace $replace,
    ) {
    }

    public function the(
        string $userPrompt,
        array $withConfig,
    ): string {
        $lastMessagesLogs = $this->listLogs->in(
            "{$withConfig['logs_filename']}/last_messages",
        );

        $template = $this->readFile->in(
            "{$withConfig['prompt_templates_filename']}/augmented.txt",
        );
        $augmentedPrompt = $this->replace->in($template, thoseParameters: [
            'last_messages' => $this->formatAsConversation->the($lastMessagesLogs),
            'user_prompt' => $userPrompt,
        ]);

        return $augmentedPrompt;
    }
}
