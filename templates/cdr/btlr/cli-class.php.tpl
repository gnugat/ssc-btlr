<?php

declare(strict_types=1);

namespace %fully_qualified_name.namespace%;

use Ssc\Btlr\App\BtlrCommand;
use Ssc\Btlr\App\BtlrCommand\ConfigureCommand;
use Ssc\Btlr\App\Stdio;
use Ssc\Btlr\App\Stdio\Write\WithStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class %fully_qualified_name.name% extends BtlrCommand
{
    public const NAME = '';
    public const ARGUMENTS = [
        'flag-name' => 'default',
    ];

    protected static $defaultName = self::NAME;

    public function __construct(
        private ConfigureCommand $configureCommand,
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

        $flagName = $input->getOption('flag-name');

        return self::SUCCESS;
    }
}
