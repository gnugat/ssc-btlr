<?php

declare(strict_types=1);

namespace Ssc\Btlr\Framework\Filesystem;

class ReadFile
{
    public function in(string $filename): string
    {
        $content = file_get_contents($filename);
        if (false === $content) {
            throw new \Exception("Failed to read: {$filename}");
        }

        return $content;
    }
}
