<?php

/*
 * This file is part of the Fossil project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Fossil;

use Gnugat\Fossil\ProjectType\Library;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Entry point for the `doc:library` command:
 * - defines the command name, arguments and options
 * - extracts parameters from the input
 * - passes the parameters to services
 * - returns the output
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class DocLibraryCommand extends DocCommand
{
    /** {@inheritdoc} */
    protected function configure()
    {
        parent::configure();

        $this->setName('doc:library');
        $this->setDescription('Bootstraps the markdown files of your library');

        $this->addOption('composer-package', 'c', InputOption::VALUE_REQUIRED, 'By default will be the same as the github-repository argument');
    }

    /** {@inheritdoc} */
    protected function getProject(InputInterface $input)
    {
        return new Library($input);
    }
}
