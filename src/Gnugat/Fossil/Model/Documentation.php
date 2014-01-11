<?php

/*
 * This file is part of the Fossil project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gnugat\Fossil\Model;

/**
 * Wraps documentation files, generated using skeleton files.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class Documentation
{
    /** @var string */
    private $absolutePathname;

    /** @var string */
    private $content;

    /**
     * @param string $absolutePathname
     * @param string $content
     */
    public function __construct($absolutePathname, $content)
    {
        $this->absolutePathname = $absolutePathname;
        $this->content = $content;
    }

    public function write()
    {
        $absolutePath = $this->getAbsolutePath();
        if (!file_exists($absolutePath)) {
            mkdir($absolutePath, 0755, true);
        }
        file_put_contents($this->absolutePathname, $this->content);
    }

    /** @return string */
    private function getAbsolutePath()
    {
        $absolutePathname = $this->absolutePathname;
        $pathPieces = explode('/', $absolutePathname);
        array_pop($pathPieces);
        $absolutePath = implode('/', $pathPieces);

        return $absolutePath;
    }
}
