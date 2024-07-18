<?php

declare(strict_types=1);

namespace tests\SscBtlr\Cdr\GenerateClassFromTemplate;

use SscBtlr\Cdr\GenerateClassFromTemplate\NewCli;
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
