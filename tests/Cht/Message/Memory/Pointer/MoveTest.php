<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Memory\Pointer;

use Ssc\Btlr\App\Filesystem\Format\WriteYamlFile;
use Ssc\Btlr\Cht\Message\Logs\MakeFilename;
use Ssc\Btlr\Cht\Message\Logs\Type;
use Ssc\Btlr\Cht\Message\Memory\Pointer\Move;
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
            'current' => './var/cht/logs/messages/1968-04-02T18:38:23+00:00_000_user_prompt.yaml',
            'previous' => './var/cht/logs/messages/1968-04-02T18:40:23+00:00_000_user_prompt.yaml',
        ];
        $toLog = [
            'entry' => 'Write code for me, please',
            'time' => '1968-04-02T18:42:23+00:00',
            'type' => Type::USER_PROMPT['name'],
        ];
        $withConfig = [
            'chunk_memory_size' => 10,
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];

        $memoryPointerFilename = "{$withConfig['logs_filename']}/memory_pointer.yaml";
        $toMakeFilename = './var/cht/logs/messages/1968-04-02T18:44:23+00:00_000_user_prompt.yaml';
        $movedMemoryPointer = [
            'current' => $toMakeFilename,
            'previous' => $memoryPointer['current'],
        ];

        // Dummies
        $makeFilename = $this->prophesize(MakeFilename::class);
        $writeYamlFile = $this->prophesize(WriteYamlFile::class);

        // Stubs & Mocks
        $makeFilename->for($toLog, $withConfig)
            ->willReturn($toMakeFilename);
        $writeYamlFile->in($memoryPointerFilename, $movedMemoryPointer)
            ->shouldBeCalled();

        // Assertion
        $move = new Move(
            $makeFilename->reveal(),
            $writeYamlFile->reveal(),
        );
        $move->the(
            $memoryPointer,
            $toLog,
            $withConfig,
        );
    }
}
