<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\DataCollection\Memory;

use Prophecy\Argument;
use Ssc\Btlr\App\Filesystem\FileExists;
use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Filesystem\WriteFile;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs;
use Ssc\Btlr\Cht\Message\DataCollection\ListLogs\Matching\Slice;
use Ssc\Btlr\Cht\Message\DataCollection\LogFilename;
use Ssc\Btlr\Cht\Message\DataCollection\Memory\Pointer;
use Ssc\Btlr\Cht\Message\DataCollection\Type;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class PointerTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_points_to_the_latest_memorised_log(): void
    {
        // Fixtures
        $withConfig = [
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];

        $lastMessagesFilename = "{$withConfig['logs_filename']}/last_messages";
        $memoryPointerFilename = "{$withConfig['logs_filename']}/memory_pointer.json";
        $logs = [
            [
                'entry' => 'Do you read me?',
                'time' => '1968-04-02T18:40:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => 'USER (1968-04-02T18:40:23+00:00): Do you read me?'
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
                'entry' => 'USER (1968-04-02T18:40:23+00:00): Do you read me?'
                    ."\nBLTR ('1968-04-02T18:40:42+00:00): Affirmative dev, I read you"
                    ."\nUSER ('1968-04-02T18:42:23+00:00): Write code for me, please"
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
        $firstLogFilename = './var/cht/logs/last_messages/1968-04-02T18:40:23+00:00_000_user_prompt.json';
        $newMemoryPointer = [
            'current' => $firstLogFilename,
            'previous' => $firstLogFilename,
        ];
        $memoryPointer = [
            'current' => './var/cht/logs/last_messages/1968-04-02T18:42:32+00:00_900_model_completion.json',
            'previous' => './var/cht/logs/last_messages/1968-04-02T18:39:23+00:00_000_user_prompt.json',
        ];

        // Dummies
        $fileExists = $this->prophesize(FileExists::class);
        $listLogs = $this->prophesize(ListLogs::class);
        $logFilename = $this->prophesize(LogFilename::class);
        $readFile = $this->prophesize(ReadFile::class);
        $writeFile = $this->prophesize(WriteFile::class);
        $slice = Argument::type(Slice::class);

        // Stubs & Mocks
        $fileExists->in($memoryPointerFilename)
            ->willReturn(true);

        $listLogs->in($lastMessagesFilename, $slice)
            ->shouldNotBeCalled();
        $logFilename->for($logs[0], $withConfig)
            ->shouldNotBeCalled();
        $writeFile->in($memoryPointerFilename, json_encode($newMemoryPointer))
            ->shouldNotBeCalled();

        $readFile->in($memoryPointerFilename)
            ->willReturn(json_encode($memoryPointer));

        // Assertion
        $pointer = new Pointer(
            $fileExists->reveal(),
            $listLogs->reveal(),
            $logFilename->reveal(),
            $readFile->reveal(),
            $writeFile->reveal(),
        );
        self::assertSame($memoryPointer, $pointer->get(
            $withConfig,
        ));
    }

    /**
     * @test
     */
    public function it_points_to_the_first_log_if_it_did_not_already_exist(): void
    {
        // Fixtures
        $withConfig = [
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];

        $lastMessagesFilename = "{$withConfig['logs_filename']}/last_messages";
        $memoryPointerFilename = "{$withConfig['logs_filename']}/memory_pointer.json";
        $logs = [
            [
                'entry' => 'Do you read me?',
                'time' => '1968-04-02T18:40:23+00:00',
                'type' => Type::USER_PROMPT['name'],
            ],
            [
                'entry' => 'USER (1968-04-02T18:40:23+00:00): Do you read me?'
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
                'entry' => 'USER (1968-04-02T18:40:23+00:00): Do you read me?'
                    ."\nBLTR ('1968-04-02T18:40:42+00:00): Affirmative dev, I read you"
                    ."\nUSER ('1968-04-02T18:42:23+00:00): Write code for me, please"
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
        $firstLogFilename = './var/cht/logs/last_messages/1968-04-02T18:40:23+00:00_000_user_prompt.json';
        $newMemoryPointer = [
            'current' => $firstLogFilename,
            'previous' => $firstLogFilename,
        ];

        // Dummies
        $fileExists = $this->prophesize(FileExists::class);
        $listLogs = $this->prophesize(ListLogs::class);
        $logFilename = $this->prophesize(LogFilename::class);
        $readFile = $this->prophesize(ReadFile::class);
        $writeFile = $this->prophesize(WriteFile::class);
        $slice = Argument::type(Slice::class);

        // Stubs & Mocks
        $fileExists->in($memoryPointerFilename)
            ->willReturn(false);

        $listLogs->in($lastMessagesFilename, $slice)
            ->willReturn($logs);
        $logFilename->for($logs[0], $withConfig)
            ->willReturn($firstLogFilename);
        $writeFile->in($memoryPointerFilename, json_encode($newMemoryPointer))
            ->shouldBeCalled();

        $readFile->in($memoryPointerFilename)
            ->willReturn(json_encode($newMemoryPointer));

        // Assertion
        $pointer = new Pointer(
            $fileExists->reveal(),
            $listLogs->reveal(),
            $logFilename->reveal(),
            $readFile->reveal(),
            $writeFile->reveal(),
        );
        self::assertSame($newMemoryPointer, $pointer->get(
            $withConfig,
        ));
    }
}
