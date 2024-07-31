<?php

declare(strict_types=1);

namespace %fully_qualified_name.namespace%;

use PHPUnit\Framework\Attributes\Test;
use SscBtlr\CdrGenerateCliTestClass\NewCli;
use tests\Ssc\Btlr\AppTest\BtlrCliTestCase;
use tests\Ssc\Btlr\AppTest\Symfony\ApplicationTesterSingleton;

class %fully_qualified_name.name% extends BtlrCliTestCase
{
    #[Test]
    public function it_(): void
    {
        $input = [
            NewCli::NAME,
            '--argument' => 'default',
        ];

        $statusCode = ApplicationTesterSingleton::get()->run($input);

        $this->shouldSucceed($statusCode);
    }
}
