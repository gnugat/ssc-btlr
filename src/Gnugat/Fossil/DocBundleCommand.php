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

use Gnugat\Fossil\ProjectType\Bundle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;

/**
 * Entry point for the `doc:bundle` command:
 * - defines the command name, arguments and options
 * - extracts parameters from the input
 * - passes the parameters to services
 * - returns the output
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class DocBundleCommand extends DocLibraryCommand
{
    /** {@inheritdoc} */
    protected function configure()
    {
        parent::configure();

        $this->setName('doc:bundle');
        $this->setDescription('Bootstraps the markdown files of your bundle');

        $this->addArgument('fully-qualified-classname', InputArgument::REQUIRED);

        $this->addOption('is-development-tool', 'd', InputOption::VALUE_NONE);
    }

    /** {@inheritdoc} */
    protected function getProject(InputInterface $input)
    {
        return new Bundle($input);
    }
}
