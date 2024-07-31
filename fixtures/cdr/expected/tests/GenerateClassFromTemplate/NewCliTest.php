<?php

declare(strict_types=1);

namespace tests\SscBtlr\Cdr\GenerateClassFromTemplate;

use PHPUnit\Framework\Attributes\Test;
use SscBtlr\Cdr\GenerateClassFromTemplate\NewCli;
use tests\Ssc\Btlr\AppTest\BtlrCliTestCase;
use tests\Ssc\Btlr\AppTest\Symfony\ApplicationTesterSingleton;

class NewCliTest extends BtlrCliTestCase
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
