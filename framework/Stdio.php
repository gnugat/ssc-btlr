<?php

declare(strict_types=1);

namespace Ssc\Btlr\Framework;

use Ssc\Btlr\Framework\Stdio\Write;
use Ssc\Btlr\Framework\Stdio\Write\WithStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Stdio
{
    public const EMPTY_LINE = '';

    public function __construct(
        private InputInterface $input,
        private OutputInterface $output,
        private Write $write = new Write(),
    ) {
    }

    public function write(
        string $message,
        string $withStyle = WithStyle::AS_REGULAR_TEXT,
    ): void {
        $this->write->the($message, $withStyle, onOutput: $this->output);
    }
}
