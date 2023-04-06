<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Stdio\Write\WithStyle;

use Ssc\Btlr\App\Stdio\Write\WithStyle;
use Symfony\Component\Console\Output\OutputInterface;

class AsRegularText implements WithStyle
{
    public function write(string $message, OutputInterface $onOutput): void
    {
        $onOutput->writeln($message);
    }
}
