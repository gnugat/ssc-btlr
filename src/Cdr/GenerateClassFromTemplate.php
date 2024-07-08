<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cdr;

use Ssc\Btlr\App\BtlrCommand;
use Ssc\Btlr\App\BtlrCommand\ConfigureCommand;
use Ssc\Btlr\App\Stdio;
use Ssc\Btlr\App\Stdio\Write\WithStyle;
use Ssc\Btlr\Cdr\Generate\ClassFromTemplate;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateClassFromTemplate extends BtlrCommand
{
    public const NAME = 'cdr:generate-class-from-template';
    public const ARGUMENTS = [
        'project-filename' => './',
        'composer-config-filename' => 'composer.json',
        'composer-parameter-namespace-path-map' => "'$.autoload-dev.psr-4.*[0]'",
        'class-template-filename' => __DIR__.'/../../templates/cdr/btlr/cli-test-class.php.tpl',
        'class-fqcn' => "'tests\\'",
    ];

    protected static $defaultName = self::NAME;

    public function __construct(
        private ConfigureCommand $configureCommand,
        private ClassFromTemplate $classFromTemplate,
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

        $projectFilename = $input->getOption('project-filename');
        $composerConfigFilename = $input->getOption('composer-config-filename');
        $composerParameterNamespacePathMap = $input->getOption('composer-parameter-namespace-path-map');
        $classTemplateFilename = $input->getOption('class-template-filename');
        $classFqcn = $input->getOption('class-fqcn');

        $classFilename = $this->classFromTemplate->using(
            $projectFilename,
            $composerConfigFilename,
            $composerParameterNamespacePathMap,
            $classTemplateFilename,
            $classFqcn,
        );

        $stdio->write("Created file {$classFilename}", WithStyle::AS_SUCCESS_BLOCK);

        return self::SUCCESS;
    }
}
