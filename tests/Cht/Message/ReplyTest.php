<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message;

use Ssc\Btlr\Cht\Message\Logs\Type;
use Ssc\Btlr\Cht\Message\Logs\WriteLog;
use Ssc\Btlr\Cht\Message\Memory\Consolidate;
use Ssc\Btlr\Cht\Message\Reply;
use Ssc\Btlr\Cht\Message\Reply\Augment;
use Ssc\Btlr\Cht\Message\Reply\UsingLlm;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class ReplyTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_replies_to_user_prompt_using_llm(): void
    {
        // Fixtures
        $userPrompt = 'Write code for me, please';
        $withConfig = [
            'chunk_memory_size' => 15,
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];

        $userPromptData = [
            'entry' => $userPrompt,
        ];
        $augmentedPrompt = "USER: {$userPrompt}\nBLTR:";
        $augmentedPromptData = [
            'entry' => $augmentedPrompt,
        ];
        $modelCompletion = "I'm sorry, dev. I'm afraid I can't do that.";
        $modelCompletionData = [
            'entry' => $modelCompletion,
            'llm_engine' => $withConfig['llm_engine'],
        ];

        // Dummies
        $augment = $this->prophesize(Augment::class);
        $consolidate = $this->prophesize(Consolidate::class);
        $usingLlm = $this->prophesize(UsingLlm::class);
        $writeLog = $this->prophesize(WriteLog::class);

        // Stubs & Mocks
        $writeLog->for($userPromptData, Type::USER_PROMPT, $withConfig)
            ->shouldBeCalled();
        $augment->the($userPrompt, $withConfig)
            ->willReturn($augmentedPrompt);
        $writeLog->for($augmentedPromptData, Type::AUGMENTED_PROMPT, $withConfig)
            ->shouldBeCalled();
        $usingLlm->complete($augmentedPrompt)
            ->willReturn($modelCompletion);
        $writeLog->for($modelCompletionData, Type::MODEL_COMPLETION, $withConfig)
            ->shouldBeCalled();
        $consolidate->memories($withConfig)
            ->shouldBeCalled();

        // Assertion
        $reply = new Reply(
            $augment->reveal(),
            $consolidate->reveal(),
            $usingLlm->reveal(),
            $writeLog->reveal(),
        );
        self::assertSame($modelCompletion, $reply->to(
            $userPrompt,
            $withConfig,
        ));
    }
}
