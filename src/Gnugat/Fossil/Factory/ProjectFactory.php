<?php

namespace Gnugat\Fossil\Factory;

use Gnugat\Fossil\Model\Bundle;
use Gnugat\Fossil\Model\Project;
use Symfony\Component\Console\Input\InputInterface;

class ProjectFactory
{
    /**
     * @param InputInterface $input
     *
     * @return Project
     */
    public function make(InputInterface $input)
    {
        if ($input->hasOption('is-bundle')) {
            return new Bundle($input);
        }

        return new Project($input);
    }
}
