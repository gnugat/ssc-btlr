<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection\Memory;

use Ssc\Btlr\App\Filesystem\FileExists;
use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Filesystem\WriteFile;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs;
use Ssc\Btlr\Cht\Message\DataCollection\LogFilename;
use Ssc\Btlr\Cht\Message\DataCollection\Memory\Pointer\Make;

class Pointer
{
    public function __construct(
        private FileExists $fileExists,
        private ListLogs $listLogs,
        private LogFilename $logFilename,
        private Make $make,
        private ReadFile $readFile,
        private WriteFile $writeFile,
    ) {
    }

    public function get(
        array $withConfig,
    ): array {
        $memoryPointerFilename = "{$withConfig['logs_filename']}/memory_pointer.json";
        if (false === $this->fileExists->in($memoryPointerFilename)) {
            return $this->make->brandNew($withConfig);
        }

        return json_decode($this->readFile->in($memoryPointerFilename), true);
    }
}
