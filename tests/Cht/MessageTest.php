<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht;

use PHPUnit\Framework\Attributes\Test;
use Ssc\Btlr\Cht\Message;
use tests\Ssc\Btlr\AppTest\BtlrCliTestCase;
use tests\Ssc\Btlr\AppTest\Symfony\ApplicationTesterSingleton;

class MessageTest extends BtlrCliTestCase
{
    #[Test]
    public function it_replies_to_user_prompt_using_llm(): void
    {
        $root = __DIR__.'/../..';
        $varTests = "{$root}/var/tests";
        $input = [
            Message::NAME,
            '--config-chunk-memory-size' => 10,
            '--config-last-messages-size' => 10,
            '--config-llm-engine' => 'chatgpt-gpt-3.5-turbo',
            '--config-logs-filename' => "{$varTests}/var/cht/logs",
            '--config-prompt-templates-filename' => "{$root}/templates/cht/prompts",
            '--manual-mode' => 'true',
        ];
        ApplicationTesterSingleton::get()->setInputs([
            'user_prompt' => 'Write code for me, please',
            'model_completion' => "I'm sorry, dev. I'm afraid I can't do that.",
        ]);

        $statusCode = ApplicationTesterSingleton::get()->run($input);

        $this->shouldSucceed($statusCode);
    }
}
