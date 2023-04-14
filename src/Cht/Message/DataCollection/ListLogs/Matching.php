<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection\ListLogs;

use Ssc\Btlr\App\Filesystem\Format\ReadYamlFile;

interface Matching
{
    public function against(array $filenames, ReadYamlFile $readYamlFile): array;
}
