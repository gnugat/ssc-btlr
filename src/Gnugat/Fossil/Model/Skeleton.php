<?php

namespace Gnugat\Fossil\Model;

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
