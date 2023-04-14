<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection;

use Ssc\Btlr\App\Filesystem\ListFiles;
use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching;

class ListLogs
{
    public function __construct(
        private ListFiles $listFiles,
        private ReadFile $readFile,
    ) {
    }

    public function in(string $logsFilename, Matching $matching): array
    {
        $logFilenames = $this->listFiles->in($logsFilename);

        return $matching->against($logFilenames, $this->readFile);
    }
}
