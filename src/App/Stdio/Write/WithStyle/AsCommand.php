<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Stdio\Write\WithStyle;

use Ssc\Btlr\App\Stdio\Write\WithStyle;
use Symfony\Component\Console\Output\OutputInterface;

class AsCommand implements WithStyle
{
    public function write(string $message, OutputInterface $onOutput): void
    {
        $style = 'fg=green';
        $onOutput->writeln("<{$style}>{$message}</>");
    }
}
