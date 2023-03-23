<?php

declare(strict_types=1);

namespace Ssc\Btlr\Framework\Stdio\Write\WithStyle;

use Ssc\Btlr\Framework\Stdio\Write\WithStyle;
use Symfony\Component\Console\Output\OutputInterface;

class AsSuccessBlock implements WithStyle
{
    public function write(string $message, OutputInterface $onOutput): void
    {
        $style = 'fg=black;bg=green';
        $paddedMessage = "  [SUCCESS] {$message}  ";
        $padding = str_repeat(' ', strlen($paddedMessage));

        $onOutput->writeln('');
        $onOutput->writeln("<{$style}>{$padding}</>");
        $onOutput->writeln("<{$style}>{$paddedMessage}</>");
        $onOutput->writeln("<{$style}>{$padding}</>");
        $onOutput->writeln('');
    }
}
