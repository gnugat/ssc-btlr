<?php

namespace Ssc\Btlr\Framework\BtlrCommand;

use Ssc\Btlr\Framework\BtlrApplication;

class InlineCommand
{
    public function using(
        string $name,
        array $arguments,
    ): string {
        $parts = ['./'.BtlrApplication::NAME, $name];
        foreach ($arguments as $name => $example) {
            $parts[] = rtrim("--{$name} {$example}");
        }

        return implode(' ', $parts);
    }
}
