<?php

/*
 * This file is part of the Fossil project.
 *
 * (c) Loïc Chardonnet <loic.chardonnet@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Gnugat\Fossil\MarkdownFile;

use Gnugat\Fossil\MarkdownFile\Documentation;
use Gnugat\Fossil\MarkdownFile\DocumentationWriter;
use Monolog\Logger;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Filesystem\Filesystem;

/** @author Loïc Chardonnet <loic.chardonnet@gmail.com> */
class DocumentationWriterSpec extends ObjectBehavior
{
    const NEW_ABSOLUTE_PATH = '/tmp/new';
    const NEW_ABSOLUTE_PATHNAME = '/tmp/new/test.md.twig';

    const EXISTING_ABSOLUTE_PATH = '/tmp/existing';
    const EXISTING_ABSOLUTE_PATHNAME = '/tmp/existing/test.md.twig';

    const CONTENT = 'Hello World!';

    function it_writes_new_files(Filesystem $filesystem, Logger $logger, Documentation $documentation)
    {
        $newAbsolutePath = '/tmp/new';
        $newAbsolutePathname = '/tmp/new/test.md.twig';
        $content = 'Hello World!';

        $filesystem->exists($newAbsolutePath)->willReturn(false);
        $filesystem->exists($newAbsolutePathname)->willReturn(false);

        $filesystem
            ->mkdir($newAbsolutePath, DocumentationWriter::DIRECTORY_MODE)
            ->shouldBeCalled()
        ;
        $filesystem
            ->dumpFile($newAbsolutePathname, $content, DocumentationWriter::FILE_MODE)
            ->shouldBeCalled()
        ;

        $this->beConstructedWith($filesystem, $logger);

        $documentation->getAbsolutePath()->willreturn($newAbsolutePath);
        $documentation->getAbsolutePathname()->willreturn($newAbsolutePathname);
        $documentation->getContent()->willreturn($content);

        $this->write($documentation);
    }

    function it_does_not_overwrite_existing_files(Filesystem $filesystem, Logger $logger, Documentation $documentation)
    {
        $oldAbsolutePath = '/tmp/old';
        $oldAbsolutePathname = '/tmp/old/test.md.twig';
        $content = 'Hello World!';

        $filesystem->exists($oldAbsolutePath)->willReturn(true);
        $filesystem->exists($oldAbsolutePathname)->willReturn(true);

        $filesystem
            ->mkdir($oldAbsolutePath, DocumentationWriter::DIRECTORY_MODE)
            ->shouldNotBeCalled()
        ;
        $filesystem
            ->dumpFile($oldAbsolutePathname, $content, DocumentationWriter::FILE_MODE)
            ->shouldNotBeCalled()
        ;

        $this->beConstructedWith($filesystem, $logger);

        $documentation->getAbsolutePath()->willreturn($oldAbsolutePath);
        $documentation->getAbsolutePathname()->willreturn($oldAbsolutePathname);
        $documentation->getContent()->willreturn($content);

        $this->write($documentation);
    }

    function it_overwrites_existing_files_when_asked_to(Filesystem $filesystem, Logger $logger, Documentation $documentation)
    {
        $oldAbsolutePath = '/tmp/old';
        $oldAbsolutePathname = '/tmp/old/test.md.twig';
        $content = 'Hello World!';

        $filesystem->exists($oldAbsolutePath)->willReturn(true);
        $filesystem->exists($oldAbsolutePathname)->willReturn(true);

        $filesystem
            ->mkdir($oldAbsolutePath, DocumentationWriter::DIRECTORY_MODE)
            ->shouldNotBeCalled()
        ;
        $filesystem
            ->dumpFile($oldAbsolutePathname, $content, DocumentationWriter::FILE_MODE)
            ->shouldBeCalled()
        ;

        $this->beConstructedWith($filesystem, $logger);
        $this->forceOverwrite();

        $documentation->getAbsolutePath()->willreturn($oldAbsolutePath);
        $documentation->getAbsolutePathname()->willreturn($oldAbsolutePathname);
        $documentation->getContent()->willreturn($content);

        $this->write($documentation);
    }
}
