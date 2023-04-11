<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection;

use Ssc\Btlr\App\Filesystem\WriteFile;
use Ssc\Btlr\App\Identifier\Uuid;
use Ssc\Btlr\App\Template\Replace;
use Ssc\Btlr\App\Time\Clock;

class WriteLog
{
    public const LOG_FILENAME_TEMPLATE = '%logs_filename%/%directory%/%time%_%priority%_%id%_%type%.json';

    public function __construct(
        private Clock $clock,
        private Replace $replace,
        private Uuid $uuid,
        private WriteFile $writeFile,
    ) {
    }

    public function for(
        string $entry,
        array $withConfig,
        array $type,
    ): void {
        $time = $this->clock->inFormat('Y-m-d\TH:i:sP');
        $id = $this->uuid->make();

        $logFilename = $this->replace->in(self::LOG_FILENAME_TEMPLATE, thoseParameters: [
            'logs_filename' => $withConfig['logs_filename'],
            'directory' => $type['directory'],
            'time' => $time,
            'priority' => $type['priority'],
            'id' => $id,
            'type' => $type['name'],
        ]);
        $this->writeFile->in($logFilename, json_encode([
            'entry' => $entry,
            'time' => $time,
            'priority' => $type['priority'],
            'id' => $id,
            'type' => $type['name'],
            'llm_engine' => $withConfig['llm_engine'],
        ]));
    }
}
