<?php

namespace Gnugat\Fossil;

use Herrera\Phar\Update\Manager;
use Herrera\Phar\Update\Manifest;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SelfUpdateCommand extends Command
{
    const MANIFEST_FILE = 'http://gnugat.github.io/fossil/manifest.json';

    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this->setName('self-update');
        $this->setAliases(array('selfupdate'));
        $this->setDescription('Updates fossil.phar to the latest version');
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $manager = new Manager(Manifest::loadFile(self::MANIFEST_FILE));
        $manager->update($this->getApplication()->getVersion(), true);
    }
}
