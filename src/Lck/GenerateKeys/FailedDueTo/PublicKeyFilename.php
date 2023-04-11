<?php

declare(strict_types=1);

namespace Ssc\Btlr\Lck\GenerateKeys\FailedDueTo;

use Ssc\Btlr\App\Failed;

class PublicKeyFilename extends Failed\DueTo
{
    public const FIELD = 'public-key-filename';
}
