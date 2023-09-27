<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Logs\ListLogs\Subset;

use Ssc\Btlr\Cht\Message\Logs\ListLogs\Subset;

class All implements Subset
{
    public function of(array $logs): array
    {
        return $logs;
    }
}
