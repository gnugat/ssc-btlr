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
    public $absolute_pathname;

    /** @var string */
    public $content;

    /**
     * @param string $absolute_pathname
     * @param string $content
     */
    public function __construct($absolute_pathname, $content)
    {
        $this->absolute_pathname = $absolute_pathname;
        $this->content = $content;
    }

    /** @return string */
    public function absolute_path()
    {
        $pathPieces = explode('/', $this->absolute_pathname);
        array_pop($pathPieces);
        $absolutePath = implode('/', $pathPieces);

        return $absolutePath;
    }
}
