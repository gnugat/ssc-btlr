<?php

namespace Gnugat\Fossil;

use Gnugat\Fossil\Factory\DocumentationFactory;
use Gnugat\Fossil\Model\Bundle;
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
class DocBundleCommand extends Command
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
        $this->setName('doc:bundle');
        $this->setDescription('Bootstraps the documentation of your bundle');

        $this->addArgument('github-repository', InputArgument::REQUIRED);
        $this->addArgument('author', InputArgument::REQUIRED);
        $this->addArgument('composer-package', InputArgument::REQUIRED);
        $this->addArgument('fully-qualified-classname', InputArgument::REQUIRED);

        $this->addOption('project-path', 'p', InputOption::VALUE_REQUIRED, '', getcwd());
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $project = new Bundle($input);
        $skeletons = $this->skeletonRepository->find();
        foreach ($skeletons as $skeleton) {
            $documentation = $this->documentationFactory->make($skeleton, $project);
            $documentation->write();
        }

        return self::RETURN_SUCCESS;
    }
}
