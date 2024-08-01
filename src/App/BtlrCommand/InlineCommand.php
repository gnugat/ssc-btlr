<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\BtlrCommand;

use Ssc\Btlr\App\BtlrApplication;

class InlineCommand
{
    /** @param array<string, ?string> $arguments */
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
