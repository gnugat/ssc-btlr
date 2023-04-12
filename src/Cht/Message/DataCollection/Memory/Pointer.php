<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection\Memory;

use Ssc\Btlr\App\Filesystem\FileExists;
use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Filesystem\WriteFile;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching\Slice;
use Ssc\Btlr\Cht\Message\DataCollection\LogFilename;

class Pointer
{
    public function __construct(
        private FileExists $fileExists,
        private ListLogs $listLogs,
        private LogFilename $logFilename,
        private ReadFile $readFile,
        private WriteFile $writeFile,
    ) {
    }

    public function get(
        array $withConfig,
    ): array {
        $memoryPointerFilename = "{$withConfig['logs_filename']}/memory_pointer.json";
        $lastMessagesFilename = "{$withConfig['logs_filename']}/last_messages";
        if (false === $this->fileExists->in($memoryPointerFilename)) {
            $logs = $this->listLogs->in(
                $lastMessagesFilename,
                matching: new Slice(0, 1),
            );
            $filename = $this->logFilename->for($logs[0], $withConfig);
            $this->writeFile->in($memoryPointerFilename, json_encode([
                'current' => $filename,
                'previous' => $filename,
            ]));
        }

        return json_decode($this->readFile->in($memoryPointerFilename), true);
    }
}
