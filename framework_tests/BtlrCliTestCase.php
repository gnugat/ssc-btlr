<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Framework;

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

    public function shouldSucceed(int $statusCode): void
    {
        self::assertSame(
            Command::SUCCESS,
            $statusCode,
            $this->app->getDisplay(),
        );
    }
}
