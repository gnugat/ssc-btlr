<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr;

use Ssc\Btlr\ListCommands;
use tests\Ssc\Btlr\AppTest\BtlrCliTestCase;

class ListCommandsTest extends BtlrCliTestCase
{
    /**
     * @test
     */
    public function it_lists_commands(): void
    {
        $input = [
            ListCommands::NAME,
        ];

        $statusCode = $this->app->run($input);

        $this->shouldSucceed($statusCode);
    }
}
