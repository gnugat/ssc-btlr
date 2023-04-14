<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching;

use Ssc\Btlr\App\Filesystem\Format\ReadYamlFile;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching;

class Slice implements Matching
{
    public function __construct(
        private int $offset,
        private ?int $length = null,
    ) {
    }

    public function against(array $filenames, ReadYamlFile $readYamlFile): array
    {
        return array_map(
            static fn ($filename) => $readYamlFile->in($filename),
            array_slice($filenames, $this->offset, $this->length),
        );
    }
}
