<?php

namespace Gnugat\Fossil;

use Gnugat\Fossil\Factory\ProjectFactory;
use Gnugat\Fossil\Factory\DocumentationFactory;
use Gnugat\Fossil\Repository\SkeletonRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Entry point for the documentation task:
 * - extracts parameters from the input
 * - passes the parameters to services
 * - returns the output
 */
class DocCommand extends Command
{
    const RETURN_SUCCESS = 0;

    /** @var ProjectFactory */
    private $projectFactory;

    /** @var DocumentationFactory */
    private $documentationFactory;

    /**
     * @param ProjectFactory       $projectFactory
     * @param SkeletonRepository   $skeletonRepository
     * @param DocumentationFactory $documentationFactory
     */
    public function __construct(ProjectFactory $projectFactory, SkeletonRepository $skeletonRepository, DocumentationFactory $documentationFactory)
    {
        $this->projectFactory = $projectFactory;
        $this->skeletonRepository = $skeletonRepository;
        $this->documentationFactory = $documentationFactory;

        parent::__construct();
    }

    /** {@inheritdoc} */
    protected function configure()
    {
        $this->setName('doc');
        $this->setDescription('Bootstraps the documentation of your project');

        $this->addArgument('github-repository', InputArgument::REQUIRED);
        $this->addArgument('author', InputArgument::REQUIRED);
        $this->addArgument('composer-package', InputArgument::REQUIRED);

        $this->addOption('project-path', 'p', InputOption::VALUE_REQUIRED, '', getcwd());
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = $this->projectFactory->make($input);
        $skeletons = $this->skeletonRepository->find();
        foreach ($skeletons as $skeleton) {
            $documentation = $this->documentationFactory->make($skeleton, $project);
            $documentation->write();
        }

        return self::RETURN_SUCCESS;
    }
}
