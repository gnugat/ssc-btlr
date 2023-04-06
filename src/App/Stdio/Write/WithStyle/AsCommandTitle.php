<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Stdio\Write\WithStyle;

use Ssc\Btlr\App\Stdio\Write\WithStyle;
use Symfony\Component\Console\Output\OutputInterface;

class AsCommandTitle implements WithStyle
{
    public function write(string $message, OutputInterface $onOutput): void
    {
        $style = 'fg=white;bg=blue';
        $paddedMessage = "  {$message}  ";
        $padding = str_repeat(' ', strlen($paddedMessage));

        $onOutput->writeln('');
        $onOutput->writeln("<{$style}>{$padding}</>");
        $onOutput->writeln("<{$style}>{$paddedMessage}</>");
        $onOutput->writeln("<{$style}>{$padding}</>");
        $onOutput->writeln('');
    }
}
