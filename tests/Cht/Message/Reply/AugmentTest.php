<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Reply;

use Prophecy\Argument;
use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Template\Replace;
use Ssc\Btlr\Cht\Message\DataCollection\LastMessages\FormatAsConversation;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching\From;
use Ssc\Btlr\Cht\Message\DataCollection\Memory\Pointer;
use Ssc\Btlr\Cht\Message\DataCollection\Type;
use Ssc\Btlr\Cht\Message\Reply\Augment;
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

        $memoryPointer = [
            'current' => './var/cht/logs/last_messages/1968-04-02T18:40:23+00:00_000_user_prompt.json',
            'previous' => './var/cht/logs/last_messages/1968-04-02T18:40:23+00:00_000_user_prompt.json',
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
        $from = Argument::type(From::class);
        $formatAsConversation = $this->prophesize(FormatAsConversation::class);
        $listLogs = $this->prophesize(ListLogs::class);
        $pointer = $this->prophesize(Pointer::class);
        $readFile = $this->prophesize(ReadFile::class);
        $replace = $this->prophesize(Replace::class);

        // Stubs & Mocks
        $pointer->get($withConfig)
            ->willReturn($memoryPointer);
        $listLogs->in("{$withConfig['logs_filename']}/last_messages", matching: $from)
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
            $pointer->reveal(),
            $readFile->reveal(),
            $replace->reveal(),
        );
        self::assertSame($augmentedPrompt, $augment->the(
            $userPrompt,
            $withConfig,
        ));
    }
}
