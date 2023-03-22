<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Framework;

use Ssc\Btlr\Framework\BtlrApplication;
use Symfony\Component\Console\Tester\ApplicationTester;

class BtlrTestApplication
{
    private static ?ApplicationTester $applicationTester = null;

    public static function make(): ApplicationTester
    {
        if (null !== self::$applicationTester) {
            return self::$applicationTester;
        }
        $application = BtlrApplication::make();
        $application->setAutoExit(false);

        self::$applicationTester = new ApplicationTester($application);

        return self::$applicationTester;
    }
}
