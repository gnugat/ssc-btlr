<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection\Memory\Pointer;

use Ssc\Btlr\App\Filesystem\WriteFile;
use Ssc\Btlr\Cht\Message\DataCollection\LogFilename;

class Move
{
    public function __construct(
        private LogFilename $logFilename,
        private WriteFile $writeFile,
    ) {
    }

    public function the(
        array $pointer,
        array $toLog,
        array $withConfig,
    ): void {
        $memoryPointerFilename = "{$withConfig['logs_filename']}/memory_pointer.json";

        $pointer['previous'] = $pointer['current'];
        $pointer['current'] = $this->logFilename->for($toLog, $withConfig);

        $this->writeFile->in($memoryPointerFilename, json_encode($pointer));
    }
}
