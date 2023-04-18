<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Message\Templates\Prompts;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Template\Replace;
use Ssc\Btlr\Cht\Message\Logs\Type;
use Ssc\Btlr\Cht\Message\Logs\WriteLog;
use Ssc\Btlr\Cht\Message\Templates\Prompts\Template;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class TemplateTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_creates_prompt_from_template(): void
    {
        // Fixtures
        $thoseParameters = [
            'last_messages' => 'USER (1968-04-02T18:40:23+00:00): Write code for me, please',
        ];
        $forType = Type::AUGMENTED_PROMPT;
        $withConfig = [
            'chunk_memory_size' => 10,
            'llm_engine' => 'chatgpt-gpt-3.5-turbo',
            'logs_filename' => './var/cht/logs',
            'prompt_templates_filename' => './templates/cht/prompts',
        ];

        $template = "LAST MESSAGES:\n%last_messages%\nBTLR:\n";
        $prompt = "LAST MESSAGES:\n{$thoseParameters['last_messages']}\nBTLR:\n";
        $data = [
            'entry' => $prompt,
        ];

        // Dummies
        $readFile = $this->prophesize(ReadFile::class);
        $replace = $this->prophesize(Replace::class);
        $writeLog = $this->prophesize(WriteLog::class);

        // Stubs & Mocks
        $readFile->in("{$withConfig['prompt_templates_filename']}/{$forType['name']}.txt")
            ->willReturn($template);
        $replace->in($template, $thoseParameters)
            ->willReturn($prompt);
        $writeLog->for($data, $forType, $withConfig)
            ->shouldBeCalled();

        // Assertion
        $template = new Template(
            $readFile->reveal(),
            $replace->reveal(),
            $writeLog->reveal(),
        );
        self::assertSame($prompt, $template->replace(
            $thoseParameters,
            $forType,
            $withConfig,
        ));
    }
}
