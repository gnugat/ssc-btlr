<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht;

use Ssc\Btlr\Cht\Augment\UsingLlm;
use Ssc\Btlr\Cht\Augment\UsingLlm\Model;
use Ssc\Btlr\Cht\Augment\UsingLlm\Model\Engine\Cli;
use Ssc\Btlr\Framework\BtlrCommand;
use Ssc\Btlr\Framework\BtlrCommand\ConfigureCommand;
use Ssc\Btlr\Framework\Stdio;
use Ssc\Btlr\Framework\Stdio\Write\WithStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Augment extends BtlrCommand
{
    public const NAME = 'cht:augment';
    public const ARGUMENTS = [
        'config-augmented-prompt-template-filename' => './templates/cht/prompts/augmented.txt',
        'config-llm-engine' => '"chatgpt-gpt-3.5-turbo"',
        'config-last-messages-filename' => './var/cht/logs/last_messages',
        'manual-mode' => 'true',
    ];

    protected static $defaultName = self::NAME;

    public function __construct(
        private ConfigureCommand $configureCommand,
        private UsingLlm $usingLlm,
        private Model $model,
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
            $this->model->switch(engine: new Cli($stdio));
        }

        $stdio->write(
            'Augments prompts to enable Infinite Memomy in LLMs',
            WithStyle::AS_COMMAND_TITLE,
        );
        $userPrompt = $stdio->ask("Provide your prompt:\n");
        $stdio->write(Stdio::EMPTY_LINE);

        $completion = $this->usingLlm->complete($userPrompt, [
            'augmented_prompt_template_filename' => $input->getOption('config-augmented-prompt-template-filename'),
            'llm_engine' => $input->getOption('config-llm-engine'),
            'last_messages_filename' => $input->getOption('config-last-messages-filename'),
        ]);
        $stdio->write('Completion:', WithStyle::AS_SUCCESS_BLOCK);
        $stdio->write($completion, WithStyle::AS_REGULAR_TEXT);

        return self::SUCCESS;
    }
}
