<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Augment\UsingLlm;

use Ssc\Btlr\Framework\Filesystem\WriteFile;
use Ssc\Btlr\Framework\Identifier\Uuid;
use Ssc\Btlr\Framework\Template\Replace;
use Ssc\Btlr\Framework\Time\Clock;

class Log
{
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
        $contentParameters = [
            'entry' => $entry,
            'id' => $this->uuid->make(),
            'llm_engine' => $withConfig['llm_engine'],
            'source' => $source,
            'time' => $this->clock->inFormat('Y-m-d\TH:i:sP'),
        ];
        $content = json_encode($contentParameters);

        $template = $withConfig['log_filename_templates'][$source];
        $thoseParameters = array_merge($contentParameters, [
            'logs_filename' => $withConfig['logs_filename'],
        ]);
        $logFilename = $this->replace->in($template, $thoseParameters);

        $this->writeFile->in($logFilename, $content);
    }
}
