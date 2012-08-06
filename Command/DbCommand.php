<?php

namespace Gnugat\QuickCommandsBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * The quick:db command class.
 *
 * @author Loic Chardonnet <loic.chardonnet@gmail.com>
 */
class DbCommand extends Command
{
    protected function configure()
    {
        $this
            ->setDescription('Drops and creates the database, updates the schema and loads the fixtures')
            ->setHelp(<<<EOT
The <info>quick:db</info> command helps you to refresh your database, schema
and fixtures.

By default, the command drops the database using
<comment>php app/console doctrine:database:drop --force</comment>, creates the
database using <comment>php app/console doctrine:database create</comment>,
updates the schema using
<comment>php app/console doctrine:schema:update</comment> and then loads the
fixtures using <comment>php app/console doctrine:fixtures:load</comment>.

<info>php app/console quick:db</info>
EOT
            )
            ->setName('quick:db')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}