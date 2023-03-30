<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Augment\UsingLlm;

use Ssc\Btlr\Cht\Augment\UsingLlm\Augment;
use Ssc\Btlr\Cht\Augment\UsingLlm\Log;
use Ssc\Btlr\Cht\Augment\UsingLlm\Log\Source;
use Ssc\Btlr\Framework\Filesystem\ReadFile;
use Ssc\Btlr\Framework\Template\Replace;
use Ssc\Btlr\TestFramework\BtlrServiceTestCase;

class AugmentTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_augments_user_prompt(): void
    {
        // Fixtures
        $userPrompt = 'Write code for me, please';
        $augmentedPromptTemplateFilename = './var/cht/prompt_templates/augmented.txt';
        $withConfig = [
            'augmented_prompt_template_filename' => $augmentedPromptTemplateFilename,
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'log_filename_templates' => [
                Source::USER_PROMPT => '%logs_filename%/conversation/%time%_000_%id%_%source%.json',
                Source::AUGMENTED_PROMPT => '"%logs_filename%/augmentations/%time%_%id%.json"',
                Source::MODEL_COMPLETION => '%logs_filename%/conversation/%time%_900_%id%_%source%.json',
            ],
        ];
        $augmentedPromptTemplate = 'Augmented %user_prompt%';
        $thoseParameters = [
            'user_prompt' => $userPrompt,
        ];
        $augmentedPrompt = "Augmented {$userPrompt}";

        // Dummies
        $log = $this->prophesize(Log::class);
        $readFile = $this->prophesize(ReadFile::class);
        $replace = $this->prophesize(Replace::class);

        // Stubs & Mocks
        $readFile->in($augmentedPromptTemplateFilename)
            ->willReturn($augmentedPromptTemplate);
        $replace->in($augmentedPromptTemplate, $thoseParameters)
            ->willReturn($augmentedPrompt);
        $log->entry($augmentedPrompt, $withConfig, Source::AUGMENTED_PROMPT)
            ->shouldBeCalled();

        // Assertion
        $augment = new Augment(
            $log->reveal(),
            $readFile->reveal(),
            $replace->reveal(),
        );
        $actualAugmentedPrompt = $augment->the(
            $userPrompt,
            $withConfig,
        );
        self::assertSame($augmentedPrompt, $actualAugmentedPrompt);
    }
}
