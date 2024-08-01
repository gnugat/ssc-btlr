<?php

declare(strict_types=1);

namespace Ssc\Btlr\Lck\GenerateKeys\FailedDueTo;

use Ssc\Btlr\App\Failed;

class PrivateKeyFilename extends Failed\DueTo
{
    public const string FIELD = 'private-key-filename';
}
