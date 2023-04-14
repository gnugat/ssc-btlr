<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection\Memory\Pointer;

use Ssc\Btlr\App\Filesystem\Format\WriteYamlFile;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching\Slice;
use Ssc\Btlr\Cht\Message\DataCollection\LogFilename;

class Make
{
    public function __construct(
        private ListLogs $listLogs,
        private LogFilename $logFilename,
        private WriteYamlFile $writeYamlFile,
    ) {
    }

    public function brandNew(
        array $withConfig,
    ): array {
        $memoryPointerFilename = "{$withConfig['logs_filename']}/memory_pointer.yaml";
        $lastMessagesFilename = "{$withConfig['logs_filename']}/last_messages";
        $logs = $this->listLogs->in(
            $lastMessagesFilename,
            matching: new Slice(0, 1),
        );
        $filename = $this->logFilename->for($logs[0], $withConfig);
        $brandNewMemoryPointer = [
            'current' => $filename,
            'previous' => $filename,
        ];
        $this->writeYamlFile->in($memoryPointerFilename, $brandNewMemoryPointer);

        return $brandNewMemoryPointer;
    }
}
