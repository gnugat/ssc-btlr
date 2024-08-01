<?php

declare(strict_types=1);

namespace Ssc\Btlr;

use Ssc\Btlr\App\BtlrCommand;
use Ssc\Btlr\App\BtlrCommand\ConfigureCommand;
use Ssc\Btlr\App\BtlrCommand\InlineCommand;
use Ssc\Btlr\App\Stdio;
use Ssc\Btlr\App\Stdio\Write\WithStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListCommands extends BtlrCommand
{
    public const string NAME = 'list-commands';
    /** @var array<string, ?string> ARGUMENTS */
    public const array ARGUMENTS = [
        'cdr' => null,
        'lck' => null,
    ];

    /** @var array<string, array<int, string>> ARGUMENTS */
    private const array COMMANDS = [
        'cdr' => [
            Cdr\GenerateClassFromTemplate::class,
            Cdr\GeneratePromptFromTemplate::class,
        ],
        'lck' => [
            Lck\GenerateKeys::class,
        ],
    ];

    protected static string $defaultName = self::NAME;

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
        $stdio = new Stdio($input, $output);
        $sections = [];
        foreach (array_keys(self::ARGUMENTS) as $sectionTitle) {
            if (true === $input->getOption($sectionTitle)) {
                $sections[] = $sectionTitle;
            }
        }

        $stdio->write(<<<LOGO
              ____  _   _      
             |  _ \| | | |     
             | |_) | |_| |_ __ 
             |  _ <| __| | '__|
             | |_) | |_| | |   
             |____/ \__|_|_|   
             Your own personal assistant

            LOGO);
        $stdio->write($this->inlineCommand->using(
            self::NAME,
            self::ARGUMENTS,
        )."\n", WithStyle::AS_COMMAND);

        foreach (self::COMMANDS as $sectionTitle => $classes) {
            if (false === in_array($sectionTitle, $sections, true) && [] !== $sections) {
                continue;
            }
            $stdio->write($sectionTitle, WithStyle::AS_SECTION_TITLE);
            foreach ($classes as $class) {
                $stdio->write('  '.$this->inlineCommand->using(
                    constant("{$class}::NAME"),
                    constant("{$class}::ARGUMENTS"),
                ), WithStyle::AS_COMMAND);
            }
        }

        return self::SUCCESS;
    }
}
