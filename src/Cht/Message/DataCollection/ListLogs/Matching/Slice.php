<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching;

class Slice implements Matching
{
    public function __construct(
        private int $offset,
        private ?int $length = null,
    ) {
    }

    public function against(array $filenames, ReadFile $readFile): array
    {
        return array_map(
            static fn ($filename) => json_decode($readFile->in($filename), true),
            array_slice($filenames, $this->offset, $this->length),
        );
    }
}
