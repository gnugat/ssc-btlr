<?php

declare(strict_types=1);

namespace Ssc\Btlr\App;

use Ssc\Btlr\App\Symfony\CommandLoader;
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
        $application->setCommandLoader(CommandLoader::make());

        return $application;
    }
}
