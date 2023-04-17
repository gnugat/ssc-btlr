<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Logs\Memory;

use Ssc\Btlr\Cht\Message\Logs\Type;

class FormatAsReport
{
    public function the(array $logs): string
    {
        $report = '';
        foreach ($logs as $log) {
            if (Type::SUMMARY['name'] === $log['type']) {
                $report .= "{$log['entry']}\n";
            }
        }

        return $report;
    }
}
