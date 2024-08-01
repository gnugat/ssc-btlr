<?php

declare(strict_types=1);

namespace Ssc\Btlr\Lck;

use Ssc\Btlr\App\BtlrCommand;
use Ssc\Btlr\App\BtlrCommand\ConfigureCommand;
use Ssc\Btlr\App\Stdio;
use Ssc\Btlr\App\Stdio\Write\WithStyle;
use Ssc\Btlr\Lck\GenerateKeys\FailedDueTo;
use Ssc\Btlr\Lck\GenerateKeys\UsingSodium;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateKeys extends BtlrCommand
{
    public const string NAME = 'lck:generate-keys';
    /** @var array<string, ?string> ARGUMENTS */
    public const array ARGUMENTS = [
        'private-key-filename' => './config/lck/private_decrypting_key',
        'public-key-filename' => './config/lck/public_encrypting_key',
    ];

    protected static string $defaultName = self::NAME;

    public function __construct(
        private ConfigureCommand $configureCommand,
        private UsingSodium $usingSodium,
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
        $privateKeyFilename = $input->getOption(
            'private-key-filename',
        );
        $publicKeyFilename = $input->getOption(
            'public-key-filename',
        );

        try {
            $this->usingSodium->saveOnFilesystem(
                $privateKeyFilename,
                $publicKeyFilename,
            );
        } catch (
            FailedDueTo\PrivateKeyFilename\AlreadyExisting
            |FailedDueTo\PublicKeyFilename\AlreadyExisting
            $error
        ) {
            $stdio->write($error->message(), WithStyle::AS_ERROR_BLOCK);

            return self::FAILURE;
        }
        $stdio->write('Keys generated', WithStyle::AS_SUCCESS_BLOCK);

        return self::SUCCESS;
    }
}
