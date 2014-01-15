<?php

/*
 * This file is part of the Fossil project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Fossil\ProjectType;

use Symfony\Component\Console\Input\InputInterface;

/**
 * Wraps the library's information provided via the input.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class Library extends Application
{
    const TYPE = 'library';

    /** @var string */
    public $composer_package;

    /** @param InputInterface $input */
    public function __construct(InputInterface $input)
    {
        parent::__construct($input);

        $this->composer_package = $input->getOption('composer-package');
        if (!isset($this->composer_package)) {
            $this->composer_package = $this->github_repository;
        }
    }
}
