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

use Monolog\Logger;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Wraps documentation files, generated using skeleton files.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class Documentation
{
    const DIRECTORY_MODE = 0755;

    const FILE_MODE = 0644;

    /** @var Filesystem */
    private $filesystem;

    /** @var Logger */
    private $logger;

    /** @var string */
    private $absolutePathname;

    /** @var string */
    private $content;

    /**
     * @param Filesystem $filesystem
     * @param Logger     $logger
     * @param string     $absolutePathname
     * @param string     $content
     */
    public function __construct(Filesystem $filesystem, Logger $logger, $absolutePathname, $content)
    {
        $this->filesystem = $filesystem;
        $this->logger = $logger;
        $this->absolutePathname = $absolutePathname;
        $this->content = $content;
    }

    /** @param bool $shouldOverwrite */
    public function write($shouldOverwrite = false)
    {
        $absolutePath = $this->getAbsolutePath();

        if (!$this->filesystem->exists($absolutePath)) {
            $this->filesystem->mkdir($absolutePath, self::DIRECTORY_MODE);

            $this->logger->info(sprintf('Created directory %s', $absolutePath));
        }

        if (!$this->filesystem->exists($this->absolutePathname) || $shouldOverwrite) {
            $this->filesystem->dumpFile($this->absolutePathname, $this->content, self::FILE_MODE);

            $this->logger->info(sprintf('Created file %s', $this->absolutePathname));
        }
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
