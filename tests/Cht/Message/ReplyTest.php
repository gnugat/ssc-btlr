<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message;

use Ssc\Btlr\Cht\Message\DataCollection\Type;
use Ssc\Btlr\Cht\Message\DataCollection\WriteLog;
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
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];
        $augmentedPrompt = "USER: {$userPrompt}\nBLTR:";
        $modelCompletion = "I'm sorry, dev. I'm afraid I can't do that.";

        // Dummies
        $augment = $this->prophesize(Augment::class);
        $usingLlm = $this->prophesize(UsingLlm::class);
        $writeLog = $this->prophesize(WriteLog::class);

        // Stubs & Mocks
        $writeLog->for($userPrompt, $withConfig, Type::USER_PROMPT)
            ->shouldBeCalled();
        $augment->the($userPrompt, $withConfig)
            ->willReturn($augmentedPrompt);
        $writeLog->for($augmentedPrompt, $withConfig, Type::AUGMENTED_PROMPT)
            ->shouldBeCalled();
        $usingLlm->complete($augmentedPrompt)
            ->willReturn($modelCompletion);
        $writeLog->for($modelCompletion, $withConfig, Type::MODEL_COMPLETION)
            ->shouldBeCalled();

        // Assertion
        $reply = new Reply(
            $augment->reveal(),
            $usingLlm->reveal(),
            $writeLog->reveal(),
        );
        self::assertSame($modelCompletion, $reply->to(
            $userPrompt,
            $withConfig,
        ));
    }
}
