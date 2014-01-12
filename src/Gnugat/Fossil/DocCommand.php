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

use Gnugat\Fossil\MarkdownFile\DocumentationFactory;
use Gnugat\Fossil\MarkdownFile\SkeletonRepository;
use Gnugat\Fossil\ProjectType\Project;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Entry point for the `doc` command:
 * - defines the command name, arguments and options
 * - extracts parameters from the input
 * - passes the parameters to services
 * - returns the output
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class DocCommand extends Command
{
    const RETURN_SUCCESS = 0;

    /** @var ProjectFactory */
    private $projectFactory;

    /** @var SkeletonRepository */
    private $skeletonRepository;

    /**
     * @param SkeletonRepository   $skeletonRepository
     * @param DocumentationFactory $documentationFactory
     */
    public function __construct(SkeletonRepository $skeletonRepository, DocumentationFactory $documentationFactory)
    {
        $this->skeletonRepository = $skeletonRepository;
        $this->documentationFactory = $documentationFactory;

        parent::__construct();
    }

    /** {@inheritdoc} */
    protected function configure()
    {
        $this->setName('doc');
        $this->setDescription('Bootstraps the markdown files of your project');

        $this->addArgument('github-repository', InputArgument::REQUIRED);
        $this->addArgument('author', InputArgument::REQUIRED);

        $this->addOption('path', 'p', InputOption::VALUE_REQUIRED, '', getcwd());
        $this->addOption('force-overwrite', 'f', InputOption::VALUE_NONE, 'Overwrite files if they already exist');
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $shouldOverwrite = $input->getOption('force-overwrite');

        $project = $this->getProject($input);
        $skeletons = $this->skeletonRepository->find();
        foreach ($skeletons as $skeleton) {
            $documentation = $this->documentationFactory->make($skeleton, $project);
            $documentation->write($shouldOverwrite);
        }

        return self::RETURN_SUCCESS;
    }

    /**
     * @param InputInterface $input
     *
     * @return Project
     */
    protected function getProject(InputInterface $input)
    {
        return new Project($input);
    }
}
