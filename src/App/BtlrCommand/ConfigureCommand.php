<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\BtlrCommand;

use Ssc\Btlr\App\BtlrCommand;
use Symfony\Component\Console\Input\InputOption;

class ConfigureCommand
{
    public function __construct(
        private InlineCommand $inlineCommand,
    ) {
    }

    public function using(
        BtlrCommand $btlrCommand,
    ): void {
        $inline = $this->inlineCommand->using(
            $btlrCommand::NAME,
            $btlrCommand::ARGUMENTS,
        );
        $btlrCommand->setHelp("<info>{$inline}</info>");
        foreach ($btlrCommand::ARGUMENTS as $name => $example) {
            $btlrCommand->addOption(
                name: $name,
                shortcut: null,
                mode: null !== $example ? InputOption::VALUE_REQUIRED : InputOption::VALUE_NONE,
            );
        }
    }
}
