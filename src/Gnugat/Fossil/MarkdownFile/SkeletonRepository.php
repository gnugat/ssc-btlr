<?php

/*
 * This file is part of the Fossil project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Fossil\MarkdownFile;

use Symfony\Component\Finder\Finder;

/**
 * Finds skeleton files and wraps them in the Skeleton model.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class SkeletonRepository
{
    /** @var Finder */
    private $finder;

    /** @var string */
    private $skeletonPath;

    /**
     * @param Finder $finder
     * @param string $skeletonsPath
     */
    public function __construct(Finder $finder, $skeletonsPath)
    {
        $this->finder = $finder;
        $this->skeletonsPath = $skeletonsPath;
    }

    /** @return array of Skeleton */
    public function find()
    {
        $files = $this->finder->files()->in($this->skeletonsPath);

        $skeletons = array();
        foreach ($files as $file) {
            $skeletons[] = new Skeleton(
                $file->getRelativePathname(),
                $file->getContents()
            );
        }

        return $skeletons;
    }
}
