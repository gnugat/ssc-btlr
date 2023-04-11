<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Reply;

use Ssc\Btlr\Cht\Message\Reply\UsingLlm;
use Ssc\Btlr\Cht\Message\Reply\UsingLlm\Engine;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class UsingLlmTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_uses_large_language_model_engines_to_complete_prompts(): void
    {
        // Fixtures
        $prompt = 'Write code for me, please';
        $completion = "I'm sorry, dev. I'm afraid I can't do that.";

        // Dummies
        $engine = $this->prophesize(Engine::class);

        // Stubs & Mocks
        $engine->complete($prompt)
            ->willReturn($completion);

        // Assertion
        $usingLlm = new UsingLlm(
            $engine->reveal(),
        );
        $actualCompletion = $usingLlm->complete(
            $prompt,
        );
        self::assertSame($completion, $actualCompletion);
    }

    /**
     * @test
     */
    public function it_can_switch_to_a_different_engine(): void
    {
        // Fixtures
        $prompt = 'Write code for me, please';
        $completion = "I'm sorry, dev. I'm afraid I can't do that.";

        // Dummies
        $originalEngine = $this->prophesize(Engine::class);
        $switchedEngine = $this->prophesize(Engine::class);

        // Stubs & Mocks
        $originalEngine->complete($prompt)
            ->shouldNotBeCalled();
        $switchedEngine->complete($prompt)
            ->willReturn($completion);

        // Assertion
        $usingLlm = new UsingLlm(
            $originalEngine->reveal(),
        );
        $usingLlm->switch(
            $switchedEngine->reveal(),
        );
        $actualCompletion = $usingLlm->complete(
            $prompt,
        );
        self::assertSame($completion, $actualCompletion);
    }
}
