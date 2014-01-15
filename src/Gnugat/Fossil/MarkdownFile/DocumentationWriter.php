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
 * Writes a documentation file.
 *
 * @author Loïc Chardonnet <loic.chardonnet@gmail.com>
 */
class DocumentationWriter
{
    const DIRECTORY_MODE = 0755;

    const FILE_MODE = 0644;

    /** @var Filesystem */
    private $filesystem;

    /** @var Logger */
    private $logger;

    /** @var bool */
    private $shouldOverwrite = false;

    /**
     * @param Filesystem $filesystem
     * @param Logger     $logger
     */
    public function __construct(Filesystem $filesystem, Logger $logger)
    {
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    public function shouldOverwrite()
    {
        $this->shouldOverwrite = true;
    }

    /** @param Documentation $documentation */
    public function write(Documentation $documentation)
    {
        $this->mkdir($documentation->absolute_path());
        $this->mkfile($documentation->absolute_pathname, $documentation->content);
    }

    /** @param string $absolutePath */
    private function mkdir($absolutePath)
    {
        if (!$this->filesystem->exists($absolutePath)) {
            $this->filesystem->mkdir($absolutePath, self::DIRECTORY_MODE);

            $this->logger->notice(sprintf('Created directory %s', $absolutePath));
        }
    }

    /**
     * @param string $absolutePathname
     * @param string $content
     */
    private function mkfile($absolutePathname, $content)
    {
        if (!$this->filesystem->exists($absolutePathname)) {
            $this->filesystem->dumpFile($absolutePathname, $content, self::FILE_MODE);

            $this->logger->notice(sprintf('Created file %s', $absolutePathname));
        } elseif ($this->shouldOverwrite) {
            $this->filesystem->dumpFile($absolutePathname, $content, self::FILE_MODE);

            $this->logger->warning(sprintf('Replaced file %s', $absolutePathname));
        }
    }
}
