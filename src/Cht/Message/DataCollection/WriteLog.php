<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection;

use Ssc\Btlr\App\Filesystem\WriteFile;
use Ssc\Btlr\App\Identifier\Uuid;
use Ssc\Btlr\App\Time\Clock;

class WriteLog
{
    public function __construct(
        private Clock $clock,
        private LogFilename $logFilename,
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

        $log = [
            'entry' => $entry,
            'time' => $time,
            'priority' => $type['priority'],
            'id' => $id,
            'type' => $type['name'],
            'llm_engine' => $withConfig['llm_engine'],
        ];
        $filename = $this->logFilename->for($log, $withConfig);
        $this->writeFile->in($filename, json_encode($log));
    }
}
