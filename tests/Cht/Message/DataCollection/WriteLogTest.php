<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\DataCollection;

use Ssc\Btlr\App\Filesystem\WriteFile;
use Ssc\Btlr\App\Identifier\Uuid;
use Ssc\Btlr\App\Time\Clock;
use Ssc\Btlr\Cht\Message\DataCollection\LogFilename;
use Ssc\Btlr\Cht\Message\DataCollection\Type;
use Ssc\Btlr\Cht\Message\DataCollection\WriteLog;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class WriteLogTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_writes_log_entry(): void
    {
        // Fixtures
        $entry = 'Write code for me, please';
        $withConfig = [
            'chunk_memory_size' => 15,
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];
        $type = Type::USER_PROMPT;

        $time = '2023-01-31T02:28:42+00:00';
        $id = '623ee9e0-5925-4e56-8171-04d69888f4c0';

        $log = [
            'entry' => $entry,
            'time' => $time,
            'priority' => $type['priority'],
            'id' => $id,
            'type' => $type['name'],
            'llm_engine' => $withConfig['llm_engine'],
        ];
        $filename = "{$withConfig['logs_filename']}"
            ."/{$type['directory']}"
            ."/{$time}"
            ."_{$type['priority']}"
            ."_{$id}"
            ."_{$type['name']}"
            .'.json';
        $logContent = json_encode($log);

        // Dummies
        $clock = $this->prophesize(Clock::class);
        $logFilename = $this->prophesize(LogFilename::class);
        $uuid = $this->prophesize(Uuid::class);
        $writeFile = $this->prophesize(WriteFile::class);

        // Stubs & Mocks
        $clock->inFormat('Y-m-d\TH:i:sP')
            ->willReturn($time);
        $uuid->make()
            ->willReturn($id);
        $logFilename->for($log, $withConfig)
            ->willReturn($filename);
        $writeFile->in($filename, $logContent)
            ->shouldBeCalled();

        // Assertion
        $writeLog = new WriteLog(
            $clock->reveal(),
            $logFilename->reveal(),
            $uuid->reveal(),
            $writeFile->reveal(),
        );
        $writeLog->for(
            $entry,
            $withConfig,
            $type,
        );
    }
}
