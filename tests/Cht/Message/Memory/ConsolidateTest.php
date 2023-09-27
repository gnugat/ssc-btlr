<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Memory;

use Prophecy\Argument;
use Ssc\Btlr\Cht\Message\Logs\ListLogs;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching\From;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Subset\All;
use Ssc\Btlr\Cht\Message\Logs\Messages\FormatAsConversation;
use Ssc\Btlr\Cht\Message\Logs\Type;
use Ssc\Btlr\Cht\Message\Logs\WriteLog;
use Ssc\Btlr\Cht\Message\Memory\Consolidate;
use Ssc\Btlr\Cht\Message\Memory\Pointer;
use Ssc\Btlr\Cht\Message\Memory\Pointer\Move;
use Ssc\Btlr\Cht\Message\Reply\UsingLlm;
use Ssc\Btlr\Cht\Message\Templates\Prompts\Template;
use Symfony\Component\Yaml\Yaml;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class ConsolidateTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_sums_up_messages_that_have_not_been_yet(): void
    {
        // Fixtures
        $withConfig = [
            'chunk_memory_size' => 3,
            'last_messages_size' => 10,
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];

        $memoryPointer = [
            'current' => './var/cht/logs/messages/1968-04-02T18:38:23+00:00_000_user_prompt.yaml',
            'previous' => './var/cht/logs/messages/1968-04-02T18:38:23+00:00_000_user_prompt.yaml',
        ];
        $newLogs = [
            [
                'entry' => 'Write code for me, please',
                'time' => '1968-04-02T18:38:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => '...',
                'time' => '1968-04-02T18:38:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
            [
                'entry' => 'Do you read me?',
                'time' => '1968-04-02T18:40:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => 'Affirmative dev, I read you',
                'time' => '1968-04-02T18:40:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
            [
                'entry' => 'Write code for me, please',
                'time' => '1968-04-02T18:42:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => "I'm afraid I can't do that, dev",
                'time' => '1968-04-02T18:42:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
        ];
        $logsToSummarize = array_slice($newLogs, 0, $withConfig['chunk_memory_size']);
        $conversationReport = 'USER (1968-04-02T18:38:23+00:00): Write code for me, please'
            ."\nBTLR (1968-04-02T18:38:42+00:00): ..."
            ."\nUSER (1968-04-02T18:40:23+00:00): Do you read me?"
            ."\nBLTR (1968-04-02T18:40:42+00:00): Affirmative dev, I read you";
        $thoseParameters = [
            'content' => $conversationReport,
        ];
        $prompt = 'Sum up this:'
            ."\n{$conversationReport}";
        $data = [
            'entry' => 'User requested code, BTLR seemed unresponsive yet acknowledged user.',
            'llm_engine' => $withConfig['llm_engine'],
        ];
        $summary = Yaml::dump($data);

        // Dummies
        $formatAsConversation = $this->prophesize(FormatAsConversation::class);
        $from = Argument::type(From::class);
        $all = Argument::type(All::class);
        $listLogs = $this->prophesize(ListLogs::class);
        $move = $this->prophesize(Move::class);
        $pointer = $this->prophesize(Pointer::class);
        $template = $this->prophesize(Template::class);
        $usingLlm = $this->prophesize(UsingLlm::class);
        $writeLog = $this->prophesize(WriteLog::class);

        // Stubs & Mocks
        $pointer->get($withConfig)
            ->willReturn($memoryPointer);
        $listLogs->in("{$withConfig['logs_filename']}/messages", matching: $from, subset: $all)
            ->willReturn($newLogs);

        $formatAsConversation->the($logsToSummarize)
            ->willReturn($conversationReport);
        $template->replace($thoseParameters, Type::SUMMARY_PROMPT, $withConfig)
            ->willReturn($prompt);
        $usingLlm->complete($prompt)
            ->willReturn($summary);
        $writeLog->for($data, Type::SUMMARY, $withConfig)
            ->shouldBeCalled();

        $move->the($memoryPointer, $newLogs[$withConfig['chunk_memory_size']], $withConfig)
            ->shouldBeCalled();

        // Assertion
        $consolidate = new Consolidate(
            $formatAsConversation->reveal(),
            $listLogs->reveal(),
            $move->reveal(),
            $pointer->reveal(),
            $template->reveal(),
            $usingLlm->reveal(),
            $writeLog->reveal(),
        );
        $consolidate->memories(
            $withConfig,
        );
    }

    /**
     * @test
     */
    public function it_does_nothing_if_there_are_not_enough_new_messages(): void
    {
        // Fixtures
        $withConfig = [
            'chunk_memory_size' => 6,
            'last_messages_size' => 10,
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];

        $memoryPointer = [
            'current' => './var/cht/logs/messages/1968-04-02T18:38:23+00:00_000_user_prompt.yaml',
            'previous' => './var/cht/logs/messages/1968-04-02T18:38:23+00:00_000_user_prompt.yaml',
        ];
        $newLogs = [
            [
                'entry' => 'Write code for me, please',
                'time' => '1968-04-02T18:38:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => 'USER (1968-04-02T18:38:23+00:00): Write code for me, please'
                    ."\nBLTR:",
                'time' => '1968-04-02T18:38:23+00:00',
                'type' => Type::AUGMENTED_PROMPT['name'],
            ],
            [
                'entry' => '...',
                'time' => '1968-04-02T18:38:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
            [
                'entry' => 'Do you read me?',
                'time' => '1968-04-02T18:40:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => 'USER (1968-04-02T18:38:23+00:00): Write code for me, please'
                    ."\nBTLR (1968-04-02T18:38:42+00:00): ..."
                    ."\nUSER (1968-04-02T18:40:23+00:00): Do you read me?"
                    ."\nBLTR:",
                'time' => '1968-04-02T18:40:23+00:00',
                'type' => Type::AUGMENTED_PROMPT['name'],
            ],
            [
                'entry' => 'Affirmative dev, I read you',
                'time' => '1968-04-02T18:40:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
        ];
        $logsToSummarize = array_slice($newLogs, 0, $withConfig['chunk_memory_size']);
        $conversationExtract = 'USER (1968-04-02T18:38:23+00:00): Write code for me, please'
            ."\nBTLR (1968-04-02T18:38:42+00:00): ..."
            ."\nUSER (1968-04-02T18:40:23+00:00): Do you read me?"
            ."\nBLTR (1968-04-02T18:40:42+00:00): Affirmative dev, I read you";
        $thoseParameters = [
            'conversation_extract' => $conversationExtract,
        ];
        $prompt = 'Sum up this:'
            ."\n{$conversationExtract}";
        $summary = 'User requested code, BTLR seemed unresponsive yet acknowledged user.';
        $toLog = Argument::type('array');

        // Dummies
        $formatAsConversation = $this->prophesize(FormatAsConversation::class);
        $all = Argument::type(All::class);
        $from = Argument::type(From::class);
        $listLogs = $this->prophesize(ListLogs::class);
        $move = $this->prophesize(Move::class);
        $pointer = $this->prophesize(Pointer::class);
        $template = $this->prophesize(Template::class);
        $usingLlm = $this->prophesize(UsingLlm::class);
        $writeLog = $this->prophesize(WriteLog::class);

        // Stubs & Mocks
        $pointer->get($withConfig)
            ->willReturn($memoryPointer);
        $listLogs->in("{$withConfig['logs_filename']}/messages", matching: $from, subset: $all)
            ->willReturn($newLogs);

        $formatAsConversation->the($logsToSummarize)
            ->shouldNotBeCalled();
        $template->replace($thoseParameters, Type::SUMMARY_PROMPT, $withConfig)
            ->shouldNotBeCalled();
        $usingLlm->complete($prompt)
            ->shouldNotBeCalled();
        $writeLog->for($summary, $withConfig, Type::SUMMARY)
            ->shouldNotBeCalled();

        $move->the($memoryPointer, $toLog, $withConfig)
            ->shouldNotBeCalled();

        // Assertion
        $consolidate = new Consolidate(
            $formatAsConversation->reveal(),
            $listLogs->reveal(),
            $move->reveal(),
            $pointer->reveal(),
            $template->reveal(),
            $usingLlm->reveal(),
            $writeLog->reveal(),
        );
        $consolidate->memories(
            $withConfig,
        );
    }
}
