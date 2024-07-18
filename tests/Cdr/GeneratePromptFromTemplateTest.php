<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cdr;

use Ssc\Btlr\Cdr\GeneratePromptFromTemplate;
use tests\Ssc\Btlr\AppTest\BtlrCliTestCase;

class GeneratePromptFromTemplateTest extends BtlrCliTestCase
{
    /**
     * @test
     */
    public function it_generates_prompt_from_template(): void
    {
        $root = __DIR__.'/../../';
        $varTests = "{$root}var/tests/";
        $projectFilename = "{$varTests}fixtures/cdr/project/";

        $input = [
            GeneratePromptFromTemplate::NAME,
            '--prompt-template-filename' => "{$root}templates/cdr/btlr/prompts/generate-code-corresponding-to-test-class.md.tpl",
            '--test-class-code-example-filename' => "{$projectFilename}tests/GeneratePromptFromTemplate/ExampleServiceOneTest.php",
            '--corresponding-class-code-example-filename' => "{$projectFilename}src/GeneratePromptFromTemplate/ExampleServiceOne.php",
            '--test-class-code-filename' => "{$projectFilename}tests/GeneratePromptFromTemplate/ServiceTwoTest.php",
        ];

        $statusCode = $this->app->run($input);

        $this->shouldSucceed($statusCode);
    }
}
