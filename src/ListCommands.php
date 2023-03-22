<?php

declare(strict_types=1);

namespace Ssc\Btlr;

use Ssc\Btlr\Framework\BtlrCommand;
use Ssc\Btlr\Framework\BtlrCommand\ConfigureCommand;
use Ssc\Btlr\Framework\BtlrCommand\InlineCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommands extends BtlrCommand
{
    public const NAME = 'list-commands';
    public const ARGUMENTS = [
    ];

    private const COMMANDS = [
    ];

    protected static $defaultName = self::NAME;

    public function __construct(
        private ConfigureCommand $configureCommand,
        private InlineCommand $inlineCommand,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->configureCommand->using($this);
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $sections = [];
        foreach (array_keys(self::ARGUMENTS) as $section) {
            if (true === $input->getOption($section)) {
                $sections[] = $section;
            }
        }

        $output->writeln(<<<LOGO
              ____  _   _      
             |  _ \| | | |     
             | |_) | |_| |_ __ 
             |  _ <| __| | '__|
             | |_) | |_| | |   
             |____/ \__|_|_|   
             Your own personal assistant
            LOGO);
        $output->writeln($this->inlineCommand->using(
            self::NAME,
            self::ARGUMENTS,
        )."\n");

        foreach (self::COMMANDS as $section => $classes) {
            if ([] === $sections && false === in_array($section, $sections, true)) {
                continue;
            }
            $output->writeln("<bg=blue>{$section}</>");
            foreach ($classes as $class) {
                $output->writeln('* '.$this->inlineCommand->using(
                    constant("{$class}::NAME"),
                    constant("{$class}::ARGUMENTS"),
                ));
            }
        }

        return self::SUCCESS;
    }
}
