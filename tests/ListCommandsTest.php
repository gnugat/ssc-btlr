<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr;

use Ssc\Btlr\ListCommands;

class ListCommandsTest extends Framework\BtlrCliTestCase
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
