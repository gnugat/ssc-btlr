<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching;

class From implements Matching
{
    public function __construct(
        private string $filename,
    ) {
    }

    public function against(array $filenames, ReadFile $readFile): array
    {
        $from = $this->filename;

        return array_values(array_map(
            static fn ($filename) => json_decode($readFile->in($filename), true),
            array_filter($filenames, static fn ($filename) => $from <= $filename),
        ));
    }
}
