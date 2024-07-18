<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\AppTest;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use tests\Ssc\Btlr\AppTest\Symfony\ApplicationTesterSingleton;

class BtlrCliTestCase extends TestCase
{
    public function shouldFail(int $statusCode): void
    {
        self::assertSame(
            Command::FAILURE,
            $statusCode,
            ApplicationTesterSingleton::get()->getDisplay(),
        );
    }

    public function shouldSucceed(int $statusCode): void
    {
        self::assertSame(
            Command::SUCCESS,
            $statusCode,
            ApplicationTesterSingleton::get()->getDisplay(),
        );
    }
}
