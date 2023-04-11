<?php

declare(strict_types=1);

namespace Ssc\Btlr\Lck\GenerateKeys;

use Ssc\Btlr\App\Filesystem\FileExists;
use Ssc\Btlr\App\Filesystem\WriteFile;
use Ssc\Btlr\App\Sodium\CryptoBox;

class UsingSodium
{
    public function __construct(
        private FileExists $fileExists,
        private WriteFile $writeFile,
        private CryptoBox $cryptoBox,
    ) {
    }

    public function saveOnFilesystem(
        string $privateKeyFilename,
        string $publicKeyFilename,
    ): void {
        if ($this->fileExists->in($privateKeyFilename)) {
            throw FailedDueTo\PrivateKeyFilename\AlreadyExisting::with($privateKeyFilename);
        }
        if ($this->fileExists->in($publicKeyFilename)) {
            throw FailedDueTo\PublicKeyFilename\AlreadyExisting::with($publicKeyFilename);
        }

        $keyPair = $this->cryptoBox->keyPair();
        $privateKey = $keyPair;
        $publicKey = $this->cryptoBox->publicKey($keyPair);

        $this->writeFile->in($privateKeyFilename, $privateKey);
        $this->writeFile->in($publicKeyFilename, $publicKey);
    }
}
