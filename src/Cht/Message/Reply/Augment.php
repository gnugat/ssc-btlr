<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Reply;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Template\Replace;
use Ssc\Btlr\Cht\Message\Logs\ListLogs;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching\From;
use Ssc\Btlr\Cht\Message\Logs\Messages\FormatAsConversation;
use Ssc\Btlr\Cht\Message\Logs\Summaries\FormatAsReport;
use Ssc\Btlr\Cht\Message\Memory\Pointer;

class Augment
{
    public function __construct(
        private FormatAsConversation $formatAsConversation,
        private FormatAsReport $formatAsReport,
        private ListLogs $listLogs,
        private Pointer $pointer,
        private ReadFile $readFile,
        private Replace $replace,
    ) {
    }

    public function the(
        string $userPrompt,
        array $withConfig,
    ): string {
        $memoryPointer = $this->pointer->get($withConfig);
        $memoryExtracts = $this->listLogs->in(
            "{$withConfig['logs_filename']}/summaries",
            matching: new From($memoryPointer['current']),
        );
        $lastMessagesLogs = $this->listLogs->in(
            "{$withConfig['logs_filename']}/messages",
            matching: new From($memoryPointer['current']),
        );

        $template = $this->readFile->in(
            "{$withConfig['prompt_templates_filename']}/augmented.txt",
        );
        $augmentedPrompt = $this->replace->in($template, thoseParameters: [
            'memory_extract' => $this->formatAsReport->the($memoryExtracts),
            'last_messages' => $this->formatAsConversation->the($lastMessagesLogs),
            'user_prompt' => $userPrompt,
        ]);

        return $augmentedPrompt;
    }
}
