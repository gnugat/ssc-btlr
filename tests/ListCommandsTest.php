<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr;

use PHPUnit\Framework\Attributes\Test;
use Ssc\Btlr\ListCommands;
use tests\Ssc\Btlr\AppTest\BtlrCliTestCase;
use tests\Ssc\Btlr\AppTest\Symfony\ApplicationTesterSingleton;

class ListCommandsTest extends BtlrCliTestCase
{
    #[Test]
    public function it_lists_commands(): void
    {
        $input = [
            ListCommands::NAME,
        ];

        $statusCode = ApplicationTesterSingleton::get()->run($input);

        $this->shouldSucceed($statusCode);
    }
}
