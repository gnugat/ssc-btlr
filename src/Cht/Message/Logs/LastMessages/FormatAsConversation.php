<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Logs\LastMessages;

use Ssc\Btlr\Cht\Message\Logs\Type;

class FormatAsConversation
{
    public function the(array $logs): string
    {
        $conversation = '';
        foreach ($logs as $log) {
            if (Type::USER_PROMPT['name'] === $log['type']) {
                $conversation .= 'USER'
                    ." ({$log['time']}):"
                    ." {$log['entry']}\n";
            }
            if (Type::MODEL_COMPLETION['name'] === $log['type']) {
                $conversation .= 'BTLR'
                    ." ({$log['time']}):"
                    ." {$log['entry']}\n";
            }
        }

        return $conversation;
    }
}
