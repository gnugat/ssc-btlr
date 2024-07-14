<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Filesystem\Format;

use Ssc\Btlr\App\Filesystem\ReadFile;

class ReadJsonFile
{
    public function __construct(
        private ReadFile $readFile,
    ) {
    }

    public function in(string $filename): array
    {
        return \json_decode($this->readFile->in($filename), flags: \JSON_OBJECT_AS_ARRAY | \JSON_THROW_ON_ERROR);
    }
}
