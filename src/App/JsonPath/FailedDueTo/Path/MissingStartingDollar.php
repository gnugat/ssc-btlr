<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\JsonPath\FailedDueTo\Path;

use Ssc\Btlr\App\JsonPath\FailedDueTo;

class MissingStartingDollar extends FailedDueTo\Path
{
    public const VIOLATED_RULE = 'starting with "$." to be valid JSON Path';
}
