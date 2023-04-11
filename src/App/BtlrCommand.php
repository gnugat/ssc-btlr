<?php

declare(strict_types=1);

namespace Ssc\Btlr\App;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

class BtlrCommand extends SymfonyCommand
{
    public static function getDefaultName(): ?string
    {
        return static::$defaultName;
    }
}
