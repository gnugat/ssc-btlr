<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Logs;

use Ssc\Btlr\App\Filesystem\Format\ReadYamlFile;
use Ssc\Btlr\App\Filesystem\ListFiles;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Subset;

class ListLogs
{
    public function __construct(
        private ListFiles $listFiles,
        private ReadYamlFile $readYamlFile,
    ) {
    }

    public function in(
        string $logsFilename,
        Matching $matching,
        Subset $subset,
    ): array {
        $logsFilenames = $this->listFiles->in($logsFilename);
        $logs = $matching->against($logsFilenames, $this->readYamlFile);

        return $subset->of($logs);
    }
}
