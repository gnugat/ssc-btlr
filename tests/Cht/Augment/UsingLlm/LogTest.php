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
        $logsFilename = './var/cht/logs';
        $source = Source::USER_PROMPT;
        $withConfig = [
            'augmented_prompt_template_filename' => './var/cht/prompt_templates/augmented.txt',
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => $logsFilename,
            'log_filename_templates' => [
                Source::USER_PROMPT => '%logs_filename%/conversation/%time%_000_%id%_%source%.json',
                Source::AUGMENTED_PROMPT => '"%logs_filename%/augmentations/%time%_%id%.json"',
                Source::MODEL_COMPLETION => '%logs_filename%/conversation/%time%_900_%id%_%source%.json',
            ],
        ];
        $id = '623ee9e0-5925-4e56-8171-04d69888f4c0';
        $time = '2023-01-31T02:28:42+00:00';
        $contentParameters = [
            'entry' => $entry,
            'id' => $id,
            'llm_engine' => $withConfig['llm_engine'],
            'source' => $source,
            'time' => $time,
        ];
        $content = json_encode($contentParameters);
        $thoseParameters = array_merge($contentParameters, [
            'logs_filename' => $logsFilename,
        ]);
        $logFilenameTemplate = $withConfig['log_filename_templates'][$source];
        $logFilename = "{$logsFilename}/conversation/{$time}_{$source}_{$id}.json";

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
        $replace->in($logFilenameTemplate, $thoseParameters)
            ->willReturn($logFilename);
        $writeFile->in($logFilename, $content)
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
