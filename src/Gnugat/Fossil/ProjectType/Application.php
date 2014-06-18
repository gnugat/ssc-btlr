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
 * Wraps the application's information provided via the input.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class Application
{
    const TYPE = 'application';

    /** @var string */
    public $github_repository;

    /** @var string */
    public $author;

    /** @var string */
    public $path;

    /** @param InputInterface $input */
    public function __construct(InputInterface $input)
    {
        $this->github_repository = $input->getArgument('github-repository');
        $this->author = $input->getArgument('author');

        $this->path = $input->getOption('path');
    }

    /** @return bool */
    public function type()
    {
        return static::TYPE;
    }

    /** @return string */
    public function documentationPath()
    {
        return 'doc';
    }

    /** @return string */
    public function directory()
    {
        $repositoryParts = explode('/', $this->github_repository);
        $projectName = $repositoryParts[1];

        return $projectName;
    }

    public function copyrightYears()
    {
        $currentYear = date('Y');
        $nextYear = date('Y', strtotime('+1 year'));

        return sprintf('%s-%s', $currentYear, $nextYear);
    }
}
