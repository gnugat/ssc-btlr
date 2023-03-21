<?php

namespace Ssc\Btlr\Framework;

use Symfony\Component\Console\Command\Command as SymfonyCommand;

class BtlrCommand extends SymfonyCommand
{
    public static function getDefaultName(): ?string
    {
        return static::$defaultName;
    }
}
