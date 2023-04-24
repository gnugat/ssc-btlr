<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Logs\ListLogs\Subset;

use Ssc\Btlr\Cht\Message\Logs\ListLogs\Subset;

class Last implements Subset
{
    public const INVALID = -2;
    public const ALL = -1;
    public const NONE = 0;

    public function __construct(
        private int $length,
    ) {
        $this->length *= -1;
        if (self::ALL === $length || self::INVALID >= $length) {
            $this->length = 0;
        }
        if (self::NONE === $length) {
            $this->length = PHP_INT_MAX;
        }
    }

    public function of(array $logs): array
    {
        return array_slice($logs, $this->length);
    }
}
