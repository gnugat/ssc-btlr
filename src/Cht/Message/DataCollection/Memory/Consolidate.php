<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection\Memory;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Template\Replace;
use Ssc\Btlr\Cht\Message\DataCollection\LastMessages\FormatAsConversation;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching\From;
use Ssc\Btlr\Cht\Message\DataCollection\Memory\Pointer\Move;
use Ssc\Btlr\Cht\Message\DataCollection\Type;
use Ssc\Btlr\Cht\Message\DataCollection\WriteLog;
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
            "{$withConfig['logs_filename']}/last_messages",
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
        $this->writeLog->for($summary, $withConfig, Type::SUMMARY);

        $toFirstUnsummarizedLog = $newLogs[$withConfig['chunk_memory_size']];
        $this->move->the($memoryPointer, $toFirstUnsummarizedLog, $withConfig);
    }
}
