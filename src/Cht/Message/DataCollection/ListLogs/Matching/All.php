<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching;

class All implements Matching
{
    public function against(array $filenames, ReadFile $readFile): array
    {
        return array_map(
            static fn ($filename) => json_decode($readFile->in($filename), true),
            $filenames,
        );
    }
}
