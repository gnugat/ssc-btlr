<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht;

use Ssc\Btlr\App\BtlrCommand;
use Ssc\Btlr\App\BtlrCommand\ConfigureCommand;
use Ssc\Btlr\App\Stdio;
use Ssc\Btlr\App\Stdio\Write\WithStyle;
use Ssc\Btlr\Cht\Message\Reply;
use Ssc\Btlr\Cht\Message\Reply\UsingLlm;
use Ssc\Btlr\Cht\Message\Reply\UsingLlm\Engine\Cli;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Message extends BtlrCommand
{
    public const NAME = 'cht:message';
    public const ARGUMENTS = [
        'config-llm-engine' => '"chatgpt-gpt-3.5-turbo"',
        'config-logs-filename' => './var/cht/logs',
        'config-prompt-templates-filename' => './templates/cht/prompts',
        'manual-mode' => 'true',
    ];

    protected static $defaultName = self::NAME;

    public function __construct(
        private ConfigureCommand $configureCommand,
        private Reply $reply,
        private UsingLlm $usingLlm,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->configureCommand->using($this);
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output,
    ): int {
        $stdio = new Stdio($input, $output);

        if ('true' === $input->getOption('manual-mode')) {
            $this->usingLlm->switch(engine: new Cli($stdio));
        }

        $stdio->write(
            'Augments prompts to enable Infinite Memomy in LLMs',
            WithStyle::AS_COMMAND_TITLE,
        );
        $userPrompt = $stdio->ask("Provide your prompt:\n");
        $stdio->write(Stdio::EMPTY_LINE);

        $response = $this->reply->to($userPrompt, withConfig: [
            'llm_engine' => $input->getOption('config-llm-engine'),
            'logs_filename' => $input->getOption('config-logs-filename'),
            'prompt_templates_filename' => $input->getOption('config-prompt-templates-filename'),
        ]);
        $stdio->write('Response:', WithStyle::AS_SUCCESS_BLOCK);
        $stdio->write($response, WithStyle::AS_REGULAR_TEXT);

        return self::SUCCESS;
    }
}
