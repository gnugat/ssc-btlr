<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching;

use Ssc\Btlr\App\Filesystem\Format\ReadYamlFile;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching;

class From implements Matching
{
    public function __construct(
        private string $filename,
    ) {
    }

    public function against(array $filenames, ReadYamlFile $readYamlFile): array
    {
        $from = $this->filename;

        return array_values(array_map(
            static fn ($filename) => $readYamlFile->in($filename),
            array_filter($filenames, static fn ($filename) => $from <= $filename),
        ));
    }
}
