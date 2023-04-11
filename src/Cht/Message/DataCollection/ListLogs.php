<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection;

use Ssc\Btlr\App\Filesystem\ListFiles;
use Ssc\Btlr\App\Filesystem\ReadFile;

class ListLogs
{
    public function __construct(
        private ListFiles $listFiles,
        private ReadFile $readFile,
    ) {
    }

    public function in(string $logsFilename): array
    {
        $logs = [];
        $logFilenames = $this->listFiles->in($logsFilename);
        foreach ($logFilenames as $logFilename) {
            $logs[] = json_decode($this->readFile->in($logFilename), true);
        }

        return $logs;
    }
}
