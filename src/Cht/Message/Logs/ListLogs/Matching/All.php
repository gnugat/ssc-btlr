<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching;

use Ssc\Btlr\App\Filesystem\Format\ReadYamlFile;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching;

class All implements Matching
{
    public function against(array $filenames, ReadYamlFile $readYamlFile): array
    {
        return array_map(
            static fn ($filename) => $readYamlFile->in($filename),
            $filenames,
        );
    }
}
