<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\DataCollection;

use Ssc\Btlr\App\Template\Replace;
use Ssc\Btlr\Cht\Message\DataCollection\LogFilename;
use Ssc\Btlr\Cht\Message\DataCollection\Type;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class LogFilenameTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_makes_a_log_filename(): void
    {
        // Fixtures
        $log = [
            'entry' => 'Write code for me, please',
            'time' => '2023-01-31T02:28:42+00:00',
            'priority' => Type::USER_PROMPT['priority'],
            'id' => '623ee9e0-5925-4e56-8171-04d69888f4c0',
            'type' => Type::USER_PROMPT['name'],
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
        ];
        $withConfig = [
            'chunk_memory_size' => 15,
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];

        $parameters = [
            'logs_filename' => $withConfig['logs_filename'],
            'directory' => Type::ALL[$log['type']]['directory'],
            'time' => $log['time'],
            'priority' => $log['priority'],
            'id' => $log['id'],
            'type' => $log['type'],
        ];
        $filename = "{$parameters['logs_filename']}"
            ."/{$parameters['directory']}"
            ."/{$parameters['time']}"
            ."_{$parameters['priority']}"
            ."_{$parameters['id']}"
            ."_{$parameters['type']}"
            .'.json';

        // Dummies
        $replace = $this->prophesize(Replace::class);

        // Stubs & Mocks
        $replace->in(LogFilename::TEMPLATE, $parameters)
            ->willReturn($filename);

        // Assertion
        $logFilename = new LogFilename(
            $replace->reveal(),
        );
        self::assertSame($filename, $logFilename->for(
            $log,
            $withConfig,
        ));
    }
}
