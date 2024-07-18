<?php

declare(strict_types=1);

namespace %fully_qualified_name.namespace%;

use PHPUnit\Framework\Attributes\Test;
use SscBtlr\CdrGenerateCliTestClass\NewCli;
use tests\Ssc\Btlr\AppTest\BtlrCliTestCase;

class %fully_qualified_name.name% extends BtlrCliTestCase
{
    #[Test]
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
