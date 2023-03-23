<?php

declare(strict_types=1);

namespace Ssc\Btlr\TestFramework;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\ApplicationTester;

class BtlrCliTestCase extends TestCase
{
    protected ApplicationTester $app;

    protected function setUp(): void
    {
        $this->app = BtlrTestApplication::make();
    }

    public function shouldFail(int $statusCode): void
    {
        self::assertSame(
            Command::FAILURE,
            $statusCode,
            $this->app->getDisplay(),
        );
    }

    public function shouldSucceed(int $statusCode): void
    {
        self::assertSame(
            Command::SUCCESS,
            $statusCode,
            $this->app->getDisplay(),
        );
    }
}
