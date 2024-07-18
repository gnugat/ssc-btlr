<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cdr;

use PHPUnit\Framework\Attributes\Test;
use Ssc\Btlr\Cdr\GenerateClassFromTemplate;
use tests\Ssc\Btlr\AppTest\BtlrCliTestCase;
use tests\Ssc\Btlr\AppTest\Symfony\ApplicationTesterSingleton;

class GenerateClassFromTemplateTest extends BtlrCliTestCase
{
    #[Test]
    public function it_generates_class_from_template(): void
    {
        $root = __DIR__.'/../../';
        $varTests = "{$root}var/tests/";

        $input = [
            GenerateClassFromTemplate::NAME,
            '--project-filename' => "{$varTests}fixtures/cdr/project/",
            '--composer-config-filename' => 'composer.json',
            '--composer-parameter-namespace-path-map' => '$.autoload-dev.psr-4.*[0]',
            '--class-template-filename' => "{$root}templates/cdr/btlr/cli-test-class.php.tpl",
            '--class-fqcn' => 'tests\\SscBtlr\\CdrGenerateClassFromTemplate\\Folder\\NewCliTest',
        ];

        $statusCode = ApplicationTesterSingleton::get()->run($input);

        $this->shouldSucceed($statusCode);
    }
}
