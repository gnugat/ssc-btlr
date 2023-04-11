<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Reply;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Template\Replace;
use Ssc\Btlr\Cht\Message\Reply\Augment;
use Ssc\Btlr\Cht\Message\Reply\Augment\FormatAsConversation;
use Ssc\Btlr\Cht\Message\Reply\Augment\ListLogs;
use Ssc\Btlr\Cht\Message\Reply\Log\Type;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class AugmentTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_augments_user_prompt(): void
    {
        // Fixtures
        $userPrompt = 'Write code for me, please';
        $withConfig = [
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];

        $lastMessagesLogs = [
            [
                'entry' => $userPrompt,
                'time' => '1968-04-02T18:40:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
        ];
        $lastMessages = "USER ({$lastMessagesLogs[0]['time']}:"
            ." {$lastMessagesLogs[0]['entry']}\n";
        $augmentedPromptTemplate = "%last_messages%BTLR:\n";
        $augmentedPromptParameters = [
            'last_messages' => $lastMessages,
            'user_prompt' => $userPrompt,
        ];
        $augmentedPrompt = "{$lastMessages}BTLR:\n";

        // Dummies
        $formatAsConversation = $this->prophesize(FormatAsConversation::class);
        $listLogs = $this->prophesize(ListLogs::class);
        $readFile = $this->prophesize(ReadFile::class);
        $replace = $this->prophesize(Replace::class);

        // Stubs & Mocks
        $listLogs->in("{$withConfig['logs_filename']}/last_messages")
            ->willReturn($lastMessagesLogs);
        $readFile->in("{$withConfig['prompt_templates_filename']}/augmented.txt")
            ->willReturn($augmentedPromptTemplate);
        $formatAsConversation->the($lastMessagesLogs)
            ->willReturn($lastMessages);
        $replace->in($augmentedPromptTemplate, $augmentedPromptParameters)
            ->willReturn($augmentedPrompt);

        // Assertion
        $augment = new Augment(
            $formatAsConversation->reveal(),
            $listLogs->reveal(),
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
