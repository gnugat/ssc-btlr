<?php

namespace Gnugat\Fossil\Model;

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
