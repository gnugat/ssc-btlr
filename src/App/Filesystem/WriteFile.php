<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Filesystem;

class WriteFile
{
    public function __construct(
        private MakeDirectories $makeDirectories,
    ) {
    }

    public function in(
        string $filename,
        string $content,
        bool $createParentDirectoriesIfTheyDontExist = true,
    ): void {
        if (true === $createParentDirectoriesIfTheyDontExist) {
            $this->makeDirectories->in($filename);
        }
        if (false === file_put_contents($filename, $content)) {
            throw new \Exception("Failed to write: {$filename}");
        }
    }
}
