<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr;

use PHPUnit\Framework\Attributes\Test;
use Ssc\Btlr\ListCommands;
use tests\Ssc\Btlr\AppTest\BtlrCliTestCase;

class ListCommandsTest extends BtlrCliTestCase
{
    #[Test]
    public function it_lists_commands(): void
    {
        $input = [
            ListCommands::NAME,
        ];

        $statusCode = $this->app->run($input);

        $this->shouldSucceed($statusCode);
    }
}
