<?php

declare(strict_types=1);

namespace Ssc\Btlr\Framework\Stdio\Write\WithStyle;

use Ssc\Btlr\Framework\Stdio\Write\WithStyle;
use Symfony\Component\Console\Output\OutputInterface;

class AsInstruction implements WithStyle
{
    public function write(string $message, OutputInterface $onOutput): void
    {
        $style = 'fg=blue';
        $onOutput->writeln("  <{$style}>{$message}</>");
    }
}
