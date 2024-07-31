<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Logs;

use PHPUnit\Framework\Attributes\Test;
use Ssc\Btlr\App\Filesystem\Format\WriteYamlFile;
use Ssc\Btlr\App\Identifier\Uuid;
use Ssc\Btlr\App\Time\Clock;
use Ssc\Btlr\Cht\Message\Logs\MakeFilename;
use Ssc\Btlr\Cht\Message\Logs\Type;
use Ssc\Btlr\Cht\Message\Logs\WriteLog;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class WriteLogTest extends BtlrServiceTestCase
{
    #[Test]
    public function it_writes_log_entry(): void
    {
        // Fixtures
        $data = [
            'entry' => 'Write code for me, please',
        ];
        $withConfig = [
            'chunk_memory_size' => 10,
            'last_messages_size' => 10,
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];
        $forType = Type::USER_PROMPT;

        $time = '2023-01-31T02:28:42+00:00';
        $id = '623ee9e0-5925-4e56-8171-04d69888f4c0';

        $log = [
            'entry' => $data['entry'],
            'time' => $time,
            'id' => $id,
            'type' => $forType['name'],
            'priority' => $forType['priority'],
        ];
        $filename = "{$withConfig['logs_filename']}"
            ."/{$forType['directory']}"
            ."/{$time}"
            ."_{$forType['priority']}"
            ."_{$id}"
            ."_{$forType['name']}"
            .'.yaml';
        $logContent = $log;

        // Dummies
        $clock = $this->prophesize(Clock::class);
        $makeFilename = $this->prophesize(MakeFilename::class);
        $uuid = $this->prophesize(Uuid::class);
        $writeYamlFile = $this->prophesize(WriteYamlFile::class);

        // Stubs & Mocks
        $clock->inFormat('Y-m-d\TH:i:sP')
            ->willReturn($time);
        $uuid->make()
            ->willReturn($id);
        $makeFilename->for($log, $withConfig)
            ->willReturn($filename);
        $writeYamlFile->in($filename, $logContent)
            ->shouldBeCalled();

        // Assertion
        $writeLog = new WriteLog(
            $clock->reveal(),
            $makeFilename->reveal(),
            $uuid->reveal(),
            $writeYamlFile->reveal(),
        );
        $writeLog->for(
            $data,
            $forType,
            $withConfig,
        );
    }
}
