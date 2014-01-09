<?php

namespace Gnugat\Fossil\Model;

use Symfony\Component\Console\Input\InputInterface;

class Project
{
    /** @var string */
    public $github_repository;

    /** @var string */
    public $author;

    /** @var string */
    public $composer_package;

    /** @var string */
    public $path;

    /** @param InputInterface $input */
    public function __construct(InputInterface $input)
    {
        $this->github_repository = $input->getArgument('github-repository');
        $this->author = $input->getArgument('author');
        $this->composer_package = $input->getArgument('composer-package');

        $this->path = $input->getOption('project-path');;
    }

    /** @return bool */
    public function is_bundle()
    {
        return false;
    }

    /** @return string */
    public function documentation_path()
    {
        return $this->path.'/doc';
    }
}
