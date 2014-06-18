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

/**
 * Wraps documentation files, generated using skeleton files.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class Documentation
{
    /** @var string */
    public $absolutePathname;

    /** @var string */
    public $content;

    /**
     * @param string $absolutePathname
     * @param string $content
     */
    public function __construct($absolutePathname, $content)
    {
        $this->absolutePathname = $absolutePathname;
        $this->content = $content;
    }

    /** @return string */
    public function getAbsolutePathname()
    {
        $pathPieces = explode('/', $this->absolutePathname);
        array_pop($pathPieces);
        $absolutePath = implode('/', $pathPieces);

        return $absolutePath;
    }

    /** @return string */
    public function getAbsolutePath()
    {
        return $this->absolutePathname;
    }

    /** @return string */
    public function getContent()
    {
        return $this->content;
    }

    /** @deprecated */
    public function absolute_path()
    {
        return $this->getAbsolutePathname();
    }
}
