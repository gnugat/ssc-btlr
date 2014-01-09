<?php

namespace Gnugat\Fossil\Repository;

use Gnugat\Fossil\Model\Skeleton;
use Symfony\Component\Finder\Finder;

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
