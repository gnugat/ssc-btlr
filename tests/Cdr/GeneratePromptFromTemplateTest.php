<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cdr;

use PHPUnit\Framework\Attributes\Test;
use Ssc\Btlr\Cdr\GeneratePromptFromTemplate;
use tests\Ssc\Btlr\AppTest\BtlrCliTestCase;
use tests\Ssc\Btlr\AppTest\Symfony\ApplicationTesterSingleton;

class GeneratePromptFromTemplateTest extends BtlrCliTestCase
{
    #[Test]
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

        $statusCode = ApplicationTesterSingleton::get()->run($input);

        $this->shouldSucceed($statusCode);
    }
}
