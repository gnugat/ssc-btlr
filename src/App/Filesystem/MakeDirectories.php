<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Filesystem;

class MakeDirectories
{
    public function in(string $filename): void
    {
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
