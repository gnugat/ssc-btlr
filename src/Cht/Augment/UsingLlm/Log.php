<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Augment\UsingLlm;

use Ssc\Btlr\Cht\Augment\UsingLlm\Log\Source;
use Ssc\Btlr\Framework\Filesystem\WriteFile;
use Ssc\Btlr\Framework\Identifier\Uuid;
use Ssc\Btlr\Framework\Template\Replace;
use Ssc\Btlr\Framework\Time\Clock;

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
