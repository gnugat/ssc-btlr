<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\DataCollection\ListLogs;

use Ssc\Btlr\App\Filesystem\ReadFile;

interface Matching
{
    public function against(array $filenames, ReadFile $readFile): array;
}
