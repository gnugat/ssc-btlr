<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Memory\Pointer;

use Ssc\Btlr\App\Filesystem\Format\WriteYamlFile;
use Ssc\Btlr\Cht\Message\Logs\MakeFilename;

class Move
{
    public function __construct(
        private MakeFilename $makeFilename,
        private WriteYamlFile $writeYamlFile,
    ) {
    }

    public function the(
        array $pointer,
        array $toLog,
        array $withConfig,
    ): void {
        $memoryPointerFilename = "{$withConfig['logs_filename']}/memory_pointer.yaml";

        $pointer['previous'] = $pointer['current'];
        $pointer['current'] = $this->makeFilename->for($toLog, $withConfig);

        $this->writeYamlFile->in($memoryPointerFilename, $pointer);
    }
}
