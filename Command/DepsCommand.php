<?php

namespace Gnugat\QuickCommandsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * The deps command class.
 *
 * @author Loic Chardonnet <loic.chardonnet@gmail.com>
 */
class DepsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setDescription('Removes the vendor directory and installs the dependencies')
            ->setHelp(<<<EOT
The <info>quick:deps</info> command helps you to install your dependencies.

By default, the command removes the vendor directory using
<comment>rm -rf ./vendor</comment> and then installs the dependencies using
<comment>composer install</comment>.

<info>php app/console quick:deps</info>
EOT
            )
            ->setName('quick:deps')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}