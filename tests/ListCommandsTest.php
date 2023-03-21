<?php

namespace tests\Ssc\Btlr;

class ListCommandsTest extends Framework\BtlrCliTestCase
{
    /**
     * @test
     */
    public function it_lists_commands(): void
    {
        $input = [
            \Ssc\Btlr\ListCommands::NAME,
        ];

        $statusCode = $this->app->run($input);

        $this->shouldSucceed($statusCode);
    }
}
