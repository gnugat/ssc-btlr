<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Augment\UsingLlm;

use Ssc\Btlr\Cht\Augment\UsingLlm\Log;
use Ssc\Btlr\Cht\Augment\UsingLlm\Log\Source;
use Ssc\Btlr\Framework\Filesystem\WriteFile;
use Ssc\Btlr\Framework\Identifier\Uuid;
use Ssc\Btlr\Framework\Template\Replace;
use Ssc\Btlr\Framework\Time\Clock;
use Ssc\Btlr\TestFramework\BtlrServiceTestCase;

class LogTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_saves_entries_on_filesystem(): void
    {
        // Fixtures
        $entry = 'Write code for me, please';
        $source = Source::USER_PROMPT;
        $lastMessagesFilename = './var/cht/logs/last_messages';
        $withConfig = [
            'augmented_prompt_template_filename' => './templates/cht/prompts/augmented.txt',
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'last_messages_filename' => $lastMessagesFilename,
        ];
        $priority = Source::PRIORITIES[$source];
        $id = '623ee9e0-5925-4e56-8171-04d69888f4c0';
        $time = '2023-01-31T02:28:42+00:00';
        $logParameters = [
            'entry' => $entry,
            'time' => $time,
            'priority' => $priority,
            'id' => $id,
            'source' => $source,
            'llm_engine' => $withConfig['llm_engine'],
            'last_messages_filename' => $lastMessagesFilename,
        ];
        $logContent = json_encode($logParameters);
        $logFilenameTemplate = Log::LOG_FILENAME_TEMPLATE;
        $logFilename = "{$lastMessagesFilename}/{$time}_{$priority}_{$id}_{$source}.json";

        // Dummies
        $clock = $this->prophesize(Clock::class);
        $replace = $this->prophesize(Replace::class);
        $uuid = $this->prophesize(Uuid::class);
        $writeFile = $this->prophesize(WriteFile::class);

        // Stubs & Mocks
        $clock->inFormat('Y-m-d\TH:i:sP')
            ->willReturn($time);
        $uuid->make()
            ->willReturn($id);
        $replace->in($logFilenameTemplate, $logParameters)
            ->willReturn($logFilename);
        $writeFile->in($logFilename, $logContent)
            ->shouldBeCalled();

        // Assertion
        $log = new Log(
            $clock->reveal(),
            $replace->reveal(),
            $uuid->reveal(),
            $writeFile->reveal(),
        );
        $log->entry(
            $entry,
            $withConfig,
            $source,
        );
    }
}
