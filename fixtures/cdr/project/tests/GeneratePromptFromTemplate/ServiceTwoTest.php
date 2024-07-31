<?php

declare(strict_types=1);

namespace tests\SscBtlr\Cdr\GeneratePromptFromTemplate;

use PHPUnit\Framework\Attributes\Test;
use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Template\Replace;
use SscBtlr\Cdr\GeneratePromptFromTemplate\ServiceTwo;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class ServiceTwoTest extends BtlrServiceTestCase
{
    #[Test]
    public function it_generates_prompt_from_template(): void
    {
        // Fixtures
        $root = __DIR__.'/../../../';
        $varTests = "{$root}var/tests/";

        $promptTemplateFilename = "{$root}templates/cdr/btlr/prompts/generate-code-corresponding-to-test-class.md.tpl";
        $projectFilename = "{$varTests}fixtures/cdr/project/";
        $testClassCodeExampleFilename = "{$projectFilename}tests/GeneratePromptFromTemplate/ExampleServiceOneTest.php";
        $correspondingClassCodeExampleFilename = "{$projectFilename}src/GeneratePromptFromTemplate/ExampleServiceOne.php";
        $testClassCodeFilename = "{$projectFilename}tests/GeneratePromptFromTemplate/ServiceTwoTest.php";

        // Dummies
        $readFile = $this->prophesize(ReadFile::class);
        $replace = $this->prophesize(Replace::class);

        // Stubs & Mocks
        $promptTemplateContent = file_get_contents($promptTemplateFilename);
        $readFile->in($promptTemplateFilename)
            ->shouldBeCalled()->willReturn($promptTemplateContent);

        $testClassCodeExampleContent = file_get_contents($testClassCodeExampleFilename);
        $readFile->in($testClassCodeExampleFilename)
            ->shouldBeCalled()->willReturn($testClassCodeExampleContent);

        $correspondingClassCodeExampleContent = file_get_contents($correspondingClassCodeExampleFilename);
        $readFile->in($correspondingClassCodeExampleFilename)
            ->shouldBeCalled()->willReturn($correspondingClassCodeExampleContent);

        $testClassCodeContent = file_get_contents($testClassCodeFilename);
        $readFile->in($testClassCodeFilename)
            ->shouldBeCalled()->willReturn($testClassCodeContent);

        $prompt = file_get_contents("{$varTests}fixtures/cdr/expected/prompts/generate-code-corresponding-to-test-class.md");
        $replace->in($promptTemplateContent, [
            'test_class_code_example' => $testClassCodeExampleContent,
            'corresponding_class_code_example' => $correspondingClassCodeExampleContent,
            'test_class_code' => $testClassCodeContent,
        ])->shouldBeCalled()->willReturn($prompt);

        // Assertion
        $serviceTwo = new ServiceTwo(
            $readFile->reveal(),
            $replace->reveal(),
        );
        self::assertSame($prompt, $serviceTwo->using(
            $promptTemplateFilename,
            $testClassCodeExampleFilename,
            $correspondingClassCodeExampleFilename,
            $testClassCodeFilename,
        ));
    }
}
