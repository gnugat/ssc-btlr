<?php

declare(strict_types=1);

namespace Ssc\Btlr\Framework\Filesystem;

class FileExists
{
    public function in(string $filename): bool
    {
        return file_exists($filename);
    }
}
