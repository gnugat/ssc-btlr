<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\DataCollection\Memory\Pointer;

use Ssc\Btlr\App\Filesystem\WriteFile;
use Ssc\Btlr\Cht\Message\DataCollection\LogFilename;
use Ssc\Btlr\Cht\Message\DataCollection\Memory\Pointer\Move;
use Ssc\Btlr\Cht\Message\DataCollection\Type;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class MoveTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_moves_the_memory_pointer_to_the_given_log(): void
    {
        // Fixtures
        $memoryPointer = [
            'current' => './var/cht/logs/last_messages/1968-04-02T18:38:23+00:00_000_user_prompt.json',
            'previous' => './var/cht/logs/last_messages/1968-04-02T18:40:23+00:00_000_user_prompt.json',
        ];
        $toLog = [
            'entry' => 'Write code for me, please',
            'time' => '1968-04-02T18:42:23+00:00',
            'type' => Type::USER_PROMPT['name'],
        ];
        $withConfig = [
            'chunk_memory_size' => 15,
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];

        $memoryPointerFilename = "{$withConfig['logs_filename']}/memory_pointer.json";
        $toLogFilename = './var/cht/logs/last_messages/1968-04-02T18:44:23+00:00_000_user_prompt.json';
        $movedMemoryPointer = [
            'current' => $toLogFilename,
            'previous' => $memoryPointer['current'],
        ];

        // Dummies
        $logFilename = $this->prophesize(LogFilename::class);
        $writeFile = $this->prophesize(WriteFile::class);

        // Stubs & Mocks
        $logFilename->for($toLog, $withConfig)
            ->willReturn($toLogFilename);
        $writeFile->in($memoryPointerFilename, json_encode($movedMemoryPointer))
            ->shouldBeCalled();

        // Assertion
        $move = new Move(
            $logFilename->reveal(),
            $writeFile->reveal(),
        );
        $move->the(
            $memoryPointer,
            $toLog,
            $withConfig,
        );
    }
}
