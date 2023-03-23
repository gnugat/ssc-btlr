<?php

declare(strict_types=1);

namespace Ssc\Btlr\Lck\GenerateKeys\FailedDueTo;

use Ssc\Btlr\Framework\Failed;

class PublicKeyFilename extends Failed\DueTo
{
    public const FIELD = 'public-key-filename';
}
