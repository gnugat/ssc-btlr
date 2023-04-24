<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Reply;

use Ssc\Btlr\Cht\Message\Logs\ListLogs;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching\From;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Subset\All;
use Ssc\Btlr\Cht\Message\Logs\Messages\FormatAsConversation;
use Ssc\Btlr\Cht\Message\Logs\Summaries\FormatAsReport;
use Ssc\Btlr\Cht\Message\Logs\Type;
use Ssc\Btlr\Cht\Message\Memory\Pointer;
use Ssc\Btlr\Cht\Message\Templates\Prompts\Template;

class Augment
{
    public function __construct(
        private FormatAsConversation $formatAsConversation,
        private FormatAsReport $formatAsReport,
        private ListLogs $listLogs,
        private Pointer $pointer,
        private Template $template,
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
            subset: new All(),
        );
        $lastMessagesLogs = $this->listLogs->in(
            "{$withConfig['logs_filename']}/messages",
            matching: new From($memoryPointer['current']),
            subset: new All(),
        );

        return $this->template->replace([
            'memory_extract' => $this->formatAsReport->the($memoryExtracts),
            'last_messages' => $this->formatAsConversation->the($lastMessagesLogs),
            'user_prompt' => $userPrompt,
        ], Type::AUGMENTED_PROMPT, $withConfig);
    }
}
