<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Logs\Summaries;

use Ssc\Btlr\Cht\Message\Logs\Type;

class FormatAsReport
{
    public function the(array $logs): string
    {
        $report = '';
        foreach ($logs as $log) {
            if (Type::SUMMARY['name'] === $log['type']) {
                $indentedEntry = str_replace("\n", "\n  ", $log['entry']);
                $report .= "  {$indentedEntry}\n\n";
            }
        }

        return rtrim($report);
    }
}
