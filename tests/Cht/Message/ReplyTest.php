<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message;

use Ssc\Btlr\Cht\Message\Reply;
use Ssc\Btlr\Cht\Message\Reply\Augment;
use Ssc\Btlr\Cht\Message\Reply\Log;
use Ssc\Btlr\Cht\Message\Reply\Log\Type;
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
        $log = $this->prophesize(Log::class);
        $usingLlm = $this->prophesize(UsingLlm::class);

        // Stubs & Mocks
        $log->entry($userPrompt, $withConfig, Type::USER_PROMPT)
            ->shouldBeCalled();
        $augment->the($userPrompt, $withConfig)
            ->willReturn($augmentedPrompt);
        $log->entry($augmentedPrompt, $withConfig, Type::AUGMENTED_PROMPT)
            ->shouldBeCalled();
        $usingLlm->complete($augmentedPrompt)
            ->willReturn($modelCompletion);
        $log->entry($modelCompletion, $withConfig, Type::MODEL_COMPLETION)
            ->shouldBeCalled();

        // Assertion
        $reply = new Reply(
            $augment->reveal(),
            $log->reveal(),
            $usingLlm->reveal(),
        );
        $response = $reply->to(
            $userPrompt,
            $withConfig,
        );
        self::assertSame($modelCompletion, $response);
    }
}
