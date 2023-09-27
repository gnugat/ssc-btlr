<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Logs\Messages;

use Ssc\Btlr\Cht\Message\Logs\Type;

class FormatAsConversation
{
    public function the(array $logs): string
    {
        $conversation = '';
        foreach ($logs as $log) {
            if (Type::USER_PROMPT['name'] === $log['type']) {
                $indentedEntry = str_replace("\n", "\n  ", $log['entry']);
                $conversation .= "USER ({$log['time']}): {$indentedEntry}\n";
            }
            if (Type::MODEL_COMPLETION['name'] === $log['type']) {
                $indentedEntry = str_replace("\n", "\n  ", $log['entry']);
                $conversation .= "BTLR ({$log['time']}): {$indentedEntry}\n";
            }
        }

        return rtrim($conversation);
    }
}
