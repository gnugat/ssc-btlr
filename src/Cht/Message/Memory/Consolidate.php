<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Memory;

use Ssc\Btlr\Cht\Message\Logs\ListLogs;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching\From;
use Ssc\Btlr\Cht\Message\Logs\Messages\FormatAsConversation;
use Ssc\Btlr\Cht\Message\Logs\Type;
use Ssc\Btlr\Cht\Message\Logs\WriteLog;
use Ssc\Btlr\Cht\Message\Memory\Pointer\Move;
use Ssc\Btlr\Cht\Message\Reply\UsingLlm;
use Ssc\Btlr\Cht\Message\Templates\Prompts\Template;

class Consolidate
{
    public function __construct(
        private FormatAsConversation $formatAsConversation,
        private ListLogs $listLogs,
        private Move $move,
        private Pointer $pointer,
        private Template $template,
        private UsingLlm $usingLlm,
        private WriteLog $writeLog,
    ) {
    }

    public function memories(
        array $withConfig,
    ): void {
        $memoryPointer = $this->pointer->get($withConfig);
        $newLogs = $this->listLogs->in(
            "{$withConfig['logs_filename']}/messages",
            matching: new From($memoryPointer['current']),
        );
        if (count($newLogs) <= $withConfig['chunk_memory_size']) {
            return;
        }

        $logsToSummarize = array_slice($newLogs, 0, $withConfig['chunk_memory_size']);
        $summaryPrompt = $this->template->replace([
            'conversation_report' => $this->formatAsConversation->the($logsToSummarize),
        ], Type::SUMMARY_PROMPT, $withConfig);
        $summary = $this->usingLlm->complete($summaryPrompt);
        $this->writeLog->for([
            'entry' => $summary,
            'llm_engine' => $withConfig['llm_engine'],
        ], Type::SUMMARY, $withConfig);

        $toFirstUnsummarizedLog = $newLogs[$withConfig['chunk_memory_size']];
        $this->move->the($memoryPointer, $toFirstUnsummarizedLog, $withConfig);
    }
}
