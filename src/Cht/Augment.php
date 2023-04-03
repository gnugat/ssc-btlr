<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht;

use Ssc\Btlr\Cht\Augment\UsingLlm;
use Ssc\Btlr\Cht\Augment\UsingLlm\Log\Source;
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
        'config-logs-filename' => './var/cht/logs',
        'config-user-prompt-log-filename-template' => '"%logs_filename%/conversation/%time%_000_%id%_%source%.json"',
        'config-augmented-prompt-log-filename-template' => '"%logs_filename%/augmentations/%time%_%id%.json"',
        'config-model-completion-log-filename-template' => '"%logs_filename%/conversation/%time%_900_%id%_%source%.json"',
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
        $configAugmentedPromptTemplateFilename = $input->getOption('config-augmented-prompt-template-filename');
        $configLlmEngine = $input->getOption('config-llm-engine');
        $configLogsFilename = $input->getOption('config-logs-filename');
        $configAugmentedPromptLogFilenameTemplate = $input->getOption('config-augmented-prompt-log-filename-template');
        $configModelCompletionLogFilenameTemplate = $input->getOption('config-model-completion-log-filename-template');
        $configUserPromptLogFilenameTemplate = $input->getOption('config-user-prompt-log-filename-template');
        $manualMode = 'true' === $input->getOption('manual-mode') ? true : false;

        $stdio->write(
            'Augments prompts to enable Infinite Memomy in LLMs',
            WithStyle::AS_COMMAND_TITLE,
        );
        if ($manualMode) {
            $this->model->switch(engine: new Cli($stdio));
        }
        $userPrompt = $stdio->ask("Provide your prompt:\n");
        $stdio->write(Stdio::EMPTY_LINE);
        $completion = $this->usingLlm->complete($userPrompt, [
            'augmented_prompt_template_filename' => $configAugmentedPromptTemplateFilename,
            'llm_engine' => $configLlmEngine,
            'logs_filename' => $configLogsFilename,
            'log_filename_templates' => [
                Source::USER_PROMPT => $configUserPromptLogFilenameTemplate,
                Source::AUGMENTED_PROMPT => $configAugmentedPromptLogFilenameTemplate,
                Source::MODEL_COMPLETION => $configModelCompletionLogFilenameTemplate,
            ],
        ]);

        $stdio->write('Completion:', WithStyle::AS_SUCCESS_BLOCK);
        $stdio->write($completion, WithStyle::AS_REGULAR_TEXT);

        return self::SUCCESS;
    }
}
