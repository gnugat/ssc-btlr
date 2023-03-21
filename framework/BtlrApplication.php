<?php

namespace Ssc\Btlr\Framework;

use Symfony\Component\Console\Application;

class BtlrApplication
{
    public const NAME = 'btlr';
    public const VERSION = '0.0.0';

    public static function make(): Application
    {
        $application = new Application(
            name: self::NAME,
            version: self::VERSION,
        );
        $application->setCommandLoader(Symfony\CommandLoader::make());

        return $application;
    }
}
