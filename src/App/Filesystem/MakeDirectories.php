<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Filesystem;

class MakeDirectories
{
    public function in(string $filename): void
    {
        // Strip file from end of the path, if present, to only get directory path
        $dirname = substr($filename, 0, strrpos($filename, '/'));

        if ('' !== $dirname && false === is_dir($dirname)) {
            mkdir(
                directory: $dirname,
                permissions: 0777,
                recursive: true,
            );
        }
    }
}
