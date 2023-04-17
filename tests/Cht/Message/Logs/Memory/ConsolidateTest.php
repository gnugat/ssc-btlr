<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Logs\Memory;

use Prophecy\Argument;
use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Template\Replace;
use Ssc\Btlr\Cht\Message\Logs\LastMessages\FormatAsConversation;
use Ssc\Btlr\Cht\Message\Logs\ListLogs;
use Ssc\Btlr\Cht\Message\Logs\ListLogs\Matching\From;
use Ssc\Btlr\Cht\Message\Logs\Memory\Consolidate;
use Ssc\Btlr\Cht\Message\Logs\Memory\Pointer;
use Ssc\Btlr\Cht\Message\Logs\Memory\Pointer\Move;
use Ssc\Btlr\Cht\Message\Logs\Type;
use Ssc\Btlr\Cht\Message\Logs\WriteLog;
use Ssc\Btlr\Cht\Message\Reply\UsingLlm;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class ConsolidateTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_sums_up_last_messages_that_have_not_been_yet(): void
    {
        // Fixtures
        $withConfig = [
            'chunk_memory_size' => 6,
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];

        $memoryPointer = [
            'current' => './var/cht/logs/last_messages/1968-04-02T18:38:23+00:00_000_user_prompt.yaml',
            'previous' => './var/cht/logs/last_messages/1968-04-02T18:38:23+00:00_000_user_prompt.yaml',
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
            [
                'entry' => 'Write code for me, please',
                'time' => '1968-04-02T18:42:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => 'USER (1968-04-02T18:38:23+00:00): Write code for me, please'
                    ."\nBTLR (1968-04-02T18:38:42+00:00): ..."
                    ."\nUSER (1968-04-02T18:40:23+00:00): Do you read me?"
                    ."\nBLTR (1968-04-02T18:40:42+00:00): Affirmative dev, I read you"
                    ."\nUSER (1968-04-02T18:42:23+00:00): Write code for me, please"
                    ."\nBLTR:",
                'time' => '1968-04-02T18:42:23+00:00',
                'type' => Type::AUGMENTED_PROMPT['name'],
            ],
            [
                'entry' => "I'm afraid I can't do that, dev",
                'time' => '1968-04-02T18:42:42+00:00',
                'type' => Type::MODEL_COMPLETION['name'],
            ],
        ];
        $logsToSummarize = array_slice($newLogs, 0, $withConfig['chunk_memory_size']);
        $template = 'Sum up this:'
            ."\n%conversation_report%";
        $conversationReport = 'USER (1968-04-02T18:38:23+00:00): Write code for me, please'
            ."\nBTLR (1968-04-02T18:38:42+00:00): ..."
            ."\nUSER (1968-04-02T18:40:23+00:00): Do you read me?"
            ."\nBLTR (1968-04-02T18:40:42+00:00): Affirmative dev, I read you";
        $thoseParameters = [
            'conversation_report' => $conversationReport,
        ];
        $prompt = 'Sum up this:'
            ."\n{$conversationReport}";
        $summary = 'User requested code, BTLR seemed unresponsive yet acknowledged user.';

        // Dummies
        $formatAsConversation = $this->prophesize(FormatAsConversation::class);
        $from = Argument::type(From::class);
        $listLogs = $this->prophesize(ListLogs::class);
        $move = $this->prophesize(Move::class);
        $pointer = $this->prophesize(Pointer::class);
        $readFile = $this->prophesize(ReadFile::class);
        $replace = $this->prophesize(Replace::class);
        $usingLlm = $this->prophesize(UsingLlm::class);
        $writeLog = $this->prophesize(WriteLog::class);

        // Stubs & Mocks
        $pointer->get($withConfig)
            ->willReturn($memoryPointer);
        $listLogs->in("{$withConfig['logs_filename']}/last_messages", matching: $from)
            ->willReturn($newLogs);

        $readFile->in("{$withConfig['prompt_templates_filename']}/summary.txt")
            ->willReturn($template);
        $formatAsConversation->the($logsToSummarize)
            ->willReturn($conversationReport);
        $replace->in($template, $thoseParameters)
            ->willReturn($prompt);
        $usingLlm->complete($prompt)
            ->willReturn($summary);
        $writeLog->for($summary, $withConfig, Type::SUMMARY)
            ->shouldBeCalled();

        $move->the($memoryPointer, $newLogs[6], $withConfig)
            ->shouldBeCalled();

        // Assertion
        $consolidate = new Consolidate(
            $formatAsConversation->reveal(),
            $listLogs->reveal(),
            $move->reveal(),
            $pointer->reveal(),
            $readFile->reveal(),
            $replace->reveal(),
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
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];

        $memoryPointer = [
            'current' => './var/cht/logs/last_messages/1968-04-02T18:38:23+00:00_000_user_prompt.yaml',
            'previous' => './var/cht/logs/last_messages/1968-04-02T18:38:23+00:00_000_user_prompt.yaml',
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
        $template = 'Sum up this:'
            ."\n%conversation_extract%";
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
        $from = Argument::type(From::class);
        $listLogs = $this->prophesize(ListLogs::class);
        $move = $this->prophesize(Move::class);
        $pointer = $this->prophesize(Pointer::class);
        $readFile = $this->prophesize(ReadFile::class);
        $replace = $this->prophesize(Replace::class);
        $usingLlm = $this->prophesize(UsingLlm::class);
        $writeLog = $this->prophesize(WriteLog::class);

        // Stubs & Mocks
        $pointer->get($withConfig)
            ->willReturn($memoryPointer);
        $listLogs->in("{$withConfig['logs_filename']}/last_messages", matching: $from)
            ->willReturn($newLogs);

        $readFile->in("{$withConfig['prompt_templates_filename']}/summary.txt")
            ->shouldNotBeCalled();
        $formatAsConversation->the($logsToSummarize)
            ->shouldNotBeCalled();
        $replace->in($template, $thoseParameters)
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
            $readFile->reveal(),
            $replace->reveal(),
            $usingLlm->reveal(),
            $writeLog->reveal(),
        );
        $consolidate->memories(
            $withConfig,
        );
    }
}
