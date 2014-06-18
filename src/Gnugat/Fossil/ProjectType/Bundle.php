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
 * Wraps the bundle's information provided via the input.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class Bundle extends Library
{
    const TYPE = 'bundle';

    /** @return string */
    public $fully_qualified_classname;

    /** @param InputInterface $input */
    public function __construct(InputInterface $input)
    {
        parent::__construct($input);

        $this->fully_qualified_classname = $input->getArgument('fully-qualified-classname');
        $this->is_development_tool = $input->getOption('is-development-tool');
    }

    /** @return string */
    public function documentationPath()
    {
        return 'Resources/doc';
    }

    /** @return string */
    public function appKernelPatternToMatch()
    {
        $pattern = '        );';
        if ($this->is_development_tool) {
            $pattern = '        }';
        }

        return $pattern;
    }

    /** @return string */
    public function appKernelPatternToReplace()
    {
        $escapedFullyQualifiedClassname = str_replace('\\', '\\\\', $this->fully_qualified_classname);
            $pattern = '                new '.$escapedFullyQualifiedClassname.'(),\n        );';
        if ($this->is_development_tool) {
            $pattern = '            $bundles[] = new '.$escapedFullyQualifiedClassname.'();\n        }';
        }

        return $pattern;
    }
}
