<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Logs\ListLogs;

interface Subset
{
    public function of(array $logs): array;
}
