<?php

declare(strict_types=1);

namespace Ssc\Btlr\Framework\Stdio\Write;

use Symfony\Component\Console\Output\OutputInterface;

interface WithStyle
{
    public const AS_COMMAND = 'AS_COMMAND';
    public const AS_REGULAR_TEXT = 'AS_REGULAR_TEXT';
    public const AS_SECTION_TITLE = 'AS_SECTION_TITLE';

    public function write(string $message, OutputInterface $onOutput): void;
}
