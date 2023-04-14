<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection;

use Ssc\Btlr\App\Template\Replace;

class LogFilename
{
    public const TEMPLATE = '%logs_filename%/%directory%/%time%_%priority%_%id%_%type%.json';

    public function __construct(
        private Replace $replace,
    ) {
    }

    public function for(
        array $log,
        array $withConfig,
    ): string {
        return $this->replace->in(self::TEMPLATE, thoseParameters: [
            'logs_filename' => $withConfig['logs_filename'],
            'directory' => Type::ALL[$log['type']]['directory'],
            'time' => $log['time'],
            'priority' => $log['priority'],
            'id' => $log['id'],
            'type' => $log['type'],
        ]);
    }
}
