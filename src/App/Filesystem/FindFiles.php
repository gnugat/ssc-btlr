<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Filesystem;

class FindFiles
{
    public function in(string $directory): array
    {
        $filenames = [];
        foreach (scandir($directory, SCANDIR_SORT_ASCENDING) as $filename) {
            if (false === in_array($filename, ['.', '..'], true)) {
                $filenames[] = "{$directory}/{$filename}";
            }
        }

        return $filenames;
    }
}
