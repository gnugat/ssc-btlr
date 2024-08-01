<?php

declare(strict_types=1);

namespace Ssc\Btlr\Lck\GenerateKeys\FailedDueTo\PublicKeyFilename;

use Ssc\Btlr\Lck\GenerateKeys\FailedDueTo;

class AlreadyExisting extends FailedDueTo\PublicKeyFilename
{
    public const string VIOLATED_RULE = 'new, but one already exists';
}
