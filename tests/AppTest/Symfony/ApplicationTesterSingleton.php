<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\AppTest\Symfony;

use Ssc\Btlr\App\BtlrApplication;
use Symfony\Component\Console\Tester\ApplicationTester;

class ApplicationTesterSingleton
{
    private static ?ApplicationTester $applicationTester = null;

    public static function get(): ApplicationTester
    {
        if (null === self::$applicationTester) {
            $application = BtlrApplication::make();
            $application->setAutoExit(false);

            self::$applicationTester = new ApplicationTester($application);
        }

        return self::$applicationTester;
    }
}
