<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Logs;

use Ssc\Btlr\App\Filesystem\Format\ReadYamlFile;
use Ssc\Btlr\App\Filesystem\ListFiles;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching;

class ListLogs
{
    public function __construct(
        private ListFiles $listFiles,
        private ReadYamlFile $readYamlFile,
    ) {
    }

    public function in(string $logsFilename, Matching $matching): array
    {
        $logFilenames = $this->listFiles->in($logsFilename);

        return $matching->against($logFilenames, $this->readYamlFile);
    }
}
