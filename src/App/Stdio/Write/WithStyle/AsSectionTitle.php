<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Stdio\Write\WithStyle;

use Ssc\Btlr\App\Stdio\Write\WithStyle;
use Symfony\Component\Console\Output\OutputInterface;

class AsSectionTitle implements WithStyle
{
    public function write(string $message, OutputInterface $onOutput): void
    {
        $style = 'fg=yellow';
        $onOutput->writeln("<{$style}>{$message}</>");
    }
}
