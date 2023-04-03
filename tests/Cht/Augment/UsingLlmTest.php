<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Augment;

use Ssc\Btlr\Cht\Augment\UsingLlm;
use Ssc\Btlr\Cht\Augment\UsingLlm\Augment;
use Ssc\Btlr\Cht\Augment\UsingLlm\Log;
use Ssc\Btlr\Cht\Augment\UsingLlm\Log\Source;
use Ssc\Btlr\Cht\Augment\UsingLlm\Model;
use Ssc\Btlr\TestFramework\BtlrServiceTestCase;

class UsingLlmTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_completes_an_augmented_version_of_the_user_prompt(): void
    {
        // Fixtures
        $userPrompt = 'Write code for me, please';
        $augmentedPromptTemplateFilename = './templates/cht/prompts/augmented.txt';
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
        $augmentedPrompt = "USER: {$userPrompt}\nBLTR:";
        $modelCompletion = "I'm sorry, dev. I'm afraid I can't do that.";

        // Dummies
        $augment = $this->prophesize(Augment::class);
        $log = $this->prophesize(Log::class);
        $model = $this->prophesize(Model::class);

        // Stubs & Mocks
        $log->entry($userPrompt, $withConfig, Source::USER_PROMPT)
            ->shouldBeCalled();
        $augment->the($userPrompt, $withConfig)
            ->willReturn($augmentedPrompt);
        $model->complete($augmentedPrompt)
            ->willReturn($modelCompletion);
        $log->entry($modelCompletion, $withConfig, Source::MODEL_COMPLETION)
            ->shouldBeCalled();

        // Assertion
        $usingLlm = new UsingLlm(
            $augment->reveal(),
            $log->reveal(),
            $model->reveal(),
        );
        $actualCompletion = $usingLlm->complete(
            $userPrompt,
            $withConfig,
        );
        self::assertSame($modelCompletion, $actualCompletion);
    }
}
