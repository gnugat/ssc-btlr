<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cdr;

use Ssc\Btlr\Cdr\GenerateClassFromTemplate;
use tests\Ssc\Btlr\AppTest\BtlrCliTestCase;

class GenerateClassFromTemplateTest extends BtlrCliTestCase
{
    /**
     * @test
     */
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

        $statusCode = $this->app->run($input);

        $this->shouldSucceed($statusCode);
    }
}
