<?php

namespace Gnugat\Fossil\Model;

use Symfony\Component\Console\Input\InputInterface;

class Bundle extends Project
{
    /** @return string */
    public $fully_qualified_classname;

    /** @param InputInterface $input */
    public function __construct(InputInterface $input)
    {
        parent::__construct($input);

        $this->fully_qualified_classname = $input->getArgument('fully-qualified-classname');
    }

    /** @return bool */
    public function is_bundle()
    {
        return true;
    }

    /** @return string */
    public function documentation_path()
    {
        return 'Resources/doc';
    }
}
