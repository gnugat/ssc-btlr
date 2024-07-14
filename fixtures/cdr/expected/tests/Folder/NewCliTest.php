<?php

declare(strict_types=1);

namespace tests\SscBtlr\CdrGenerateClassFromTemplate\Folder;

use SscBtlr\CdrGenerateClassFromTemplate\NewCli;
use tests\Ssc\Btlr\AppTest\BtlrCliTestCase;

class NewCliTest extends BtlrCliTestCase
{
    /**
     * @test
     */
    public function it_(): void
    {
        $input = [
            NewCli::NAME,
            '--argument' => 'default',
        ];

        $statusCode = $this->app->run($input);

        $this->shouldSucceed($statusCode);
    }
}
