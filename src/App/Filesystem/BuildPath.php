<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Filesystem;

class BuildPath
{
    public function joining(string ...$subPaths): string
    {
        $trimmedPaths = array_map(static function ($subPath) {
            return trim($subPath, '/');
        }, $subPaths);

        $path = implode('/', $trimmedPaths);

        // Handle root paths and other special cases where the initial sub-path should not be trimmed.
        if (true === str_starts_with($subPaths[0], '/')) {
            $path = '/'.$path;
        }

        return $path;
    }
}
