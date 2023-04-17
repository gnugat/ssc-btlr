<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Memory;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Template\Replace;
use Ssc\Btlr\Cht\Message\Logs\ListLogs;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching\From;
use Ssc\Btlr\Cht\Message\Logs\Messages\FormatAsConversation;
use Ssc\Btlr\Cht\Message\Logs\Type;
use Ssc\Btlr\Cht\Message\Logs\WriteLog;
use Ssc\Btlr\Cht\Message\Memory\Pointer\Move;
use Ssc\Btlr\Cht\Message\Reply\UsingLlm;

class Consolidate
{
    public function __construct(
        private FormatAsConversation $formatAsConversation,
        private ListLogs $listLogs,
        private Move $move,
        private Pointer $pointer,
        private ReadFile $readFile,
        private Replace $replace,
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
        $template = $this->readFile->in(
            "{$withConfig['prompt_templates_filename']}/summary.txt",
        );
        $prompt = $this->replace->in($template, thoseParameters: [
            'conversation_report' => $this->formatAsConversation->the($logsToSummarize),
        ]);
        $summary = $this->usingLlm->complete($prompt);
        $this->writeLog->for([
            'entry' => $summary,
            'llm_engine' => $withConfig['llm_engine'],
        ], Type::SUMMARY, $withConfig);

        $toFirstUnsummarizedLog = $newLogs[$withConfig['chunk_memory_size']];
        $this->move->the($memoryPointer, $toFirstUnsummarizedLog, $withConfig);
    }
}
