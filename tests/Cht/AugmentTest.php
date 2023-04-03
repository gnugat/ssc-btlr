<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht;

use Ssc\Btlr\Cht\Augment;
use Ssc\Btlr\TestFramework\BtlrCliTestCase;

class AugmentTest extends BtlrCliTestCase
{
    /**
     * @test
     */
    public function it_augments_user_prompt(): void
    {
        $root = __DIR__.'/../..';
        $varTests = "{$root}/var/tests";
        $input = [
            Augment::NAME,
            '--config-augmented-prompt-template-filename' => "{$root}/templates/cht/prompts/augmented.txt",
            '--config-llm-engine' => 'chatgpt-gpt-3.5-turbo',
            '--config-last-messages-filename' => "{$varTests}/var/cht/logs/last_messages",
            '--manual-mode' => 'true',
        ];
        $this->app->setInputs([
            'user_prompt' => 'Write code for me, please',
            'model_completion' => "I'm sorry, dev. I'm afraid I can't do that.",
        ]);

        $statusCode = $this->app->run($input);

        $this->shouldSucceed($statusCode);
    }
}
