<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Augment\UsingLlm;

use Ssc\Btlr\App\Filesystem\WriteFile;
use Ssc\Btlr\App\Identifier\Uuid;
use Ssc\Btlr\App\Template\Replace;
use Ssc\Btlr\App\Time\Clock;
use Ssc\Btlr\Cht\Augment\UsingLlm\Log\Source;

class Log
{
    public const LOG_FILENAME_TEMPLATE = '%last_messages_filename%/%time%_%priority%_%id%_%source%.json';

    public function __construct(
        private Clock $clock,
        private Replace $replace,
        private Uuid $uuid,
        private WriteFile $writeFile,
    ) {
    }

    public function entry(
        string $entry,
        array $withConfig,
        string $source,
    ): void {
        $logParameters = [
            'entry' => $entry,
            'time' => $this->clock->inFormat('Y-m-d\TH:i:sP'),
            'priority' => Source::PRIORITIES[$source],
            'id' => $this->uuid->make(),
            'source' => $source,
            'llm_engine' => $withConfig['llm_engine'],
            'last_messages_filename' => $withConfig['last_messages_filename'],
        ];
        $logFilename = $this->replace->in(self::LOG_FILENAME_TEMPLATE, $logParameters);

        $this->writeFile->in($logFilename, json_encode($logParameters));
    }
}
