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
use Gnugat\Fossil\MarkdownFile\DocumentationWriter;
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
    protected $projectFactory;

    /** @var SkeletonRepository */
    protected $skeletonRepository;

    /** @var DocumentationWriter */
    protected $documentationWriter;

    /**
     * @param SkeletonRepository   $skeletonRepository
     * @param DocumentationFactory $documentationFactory
     * @param DocumentationWriter  $documentationWriter
     */
    public function __construct(SkeletonRepository $skeletonRepository, DocumentationFactory $documentationFactory, DocumentationWriter $documentationWriter)
    {
        $this->skeletonRepository = $skeletonRepository;
        $this->documentationFactory = $documentationFactory;
        $this->documentationWriter = $documentationWriter;

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
        if ($input->getOption('force-overwrite')) {
            $this->documentationWriter->shouldOverwrite();
        }

        $project = $this->getProject($input);
        $skeletons = $this->skeletonRepository->find();
        foreach ($skeletons as $skeleton) {
            $documentation = $this->documentationFactory->make($skeleton, $project);
            $this->documentationWriter->write($documentation);
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
