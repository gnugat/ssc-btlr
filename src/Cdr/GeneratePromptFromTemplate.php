<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cdr;

use Ssc\Btlr\App\BtlrCommand;
use Ssc\Btlr\App\BtlrCommand\ConfigureCommand;
use Ssc\Btlr\App\Stdio;
use Ssc\Btlr\App\Stdio\Write\WithStyle;
use Ssc\Btlr\Cdr\Generate\PromptFromTemplate;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratePromptFromTemplate extends BtlrCommand
{
    public const NAME = 'cdr:generate-prompt-from-template';
    public const ARGUMENTS = [
        'prompt-template-filename' => __DIR__.'/../../templates/cdr/btlr/prompts/generate-code-corresponding-to-test-class.md.tpl',
        'test-class-code-example-filename' => './tests/',
        'corresponding-class-code-example-filename' => './src/',
        'test-class-code-filename' => './tests/',
    ];

    protected static $defaultName = self::NAME;

    public function __construct(
        private ConfigureCommand $configureCommand,
        private PromptFromTemplate $promptFromTemplate,
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

        $promptTemplateFilename = $input->getOption('prompt-template-filename');
        $testClassCodeExampleFilename = $input->getOption('test-class-code-example-filename');
        $correspondingClassCodeExampleFilename = $input->getOption('corresponding-class-code-example-filename');
        $testClassCodeFilename = $input->getOption('test-class-code-filename');

        $prompt = $this->promptFromTemplate->using(
            $promptTemplateFilename,
            $testClassCodeExampleFilename,
            $correspondingClassCodeExampleFilename,
            $testClassCodeFilename,
        );

        $stdio->write('Prompt:', WithStyle::AS_SUCCESS_BLOCK);
        $stdio->write($prompt, WithStyle::AS_REGULAR_TEXT);

        return self::SUCCESS;
    }
}
