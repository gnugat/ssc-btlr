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
 * Wraps skeleton files.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class Skeleton
{
    /** @var string */
    public $relative_pathname;

    /** @var string */
    public $content;

    /**
     * @param string $relativePathname
     * @param string $content
     */
    public function __construct($relativePathname, $content)
    {
        $this->relative_pathname = $relativePathname;
        $this->content = $content;
    }

    /** @var bool */
    public function isInsideDocumentationDirectory()
    {
        return false !== strpos($this->relative_pathname, 'doc');
    }
}
