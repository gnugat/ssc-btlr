<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Stdio\Write;

use Symfony\Component\Console\Output\OutputInterface;

interface WithStyle
{
    public const string AS_COMMAND = 'AS_COMMAND';
    public const string AS_COMMAND_TITLE = 'AS_COMMAND_TITLE';
    public const string AS_ERROR_BLOCK = 'AS_ERROR_BLOCK';
    public const string AS_INSTRUCTION = 'AS_INSTRUCTION';
    public const string AS_REGULAR_TEXT = 'AS_REGULAR_TEXT';
    public const string AS_SECTION_TITLE = 'AS_SECTION_TITLE';
    public const string AS_SUCCESS_BLOCK = 'AS_SUCCESS_BLOCK';

    public function write(string $message, OutputInterface $onOutput): void;
}
