<?php

declare(strict_types=1);

namespace Ssc\Btlr\Lck\GenerateKeys\FailedDueTo\PrivateKeyFilename;

use Ssc\Btlr\Lck\GenerateKeys\FailedDueTo;

class AlreadyExisting extends FailedDueTo\PrivateKeyFilename
{
    public const VIOLATED_RULE = 'new, but one already exists';
}
