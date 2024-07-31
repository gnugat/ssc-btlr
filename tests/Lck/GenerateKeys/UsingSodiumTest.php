<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Lck\GenerateKeys;

use PHPUnit\Framework\Attributes\Test;
use Ssc\Btlr\App\Filesystem\FileExists;
use Ssc\Btlr\App\Filesystem\WriteFile;
use Ssc\Btlr\App\Sodium\CryptoBox;
use Ssc\Btlr\Lck\GenerateKeys\FailedDueTo;
use Ssc\Btlr\Lck\GenerateKeys\UsingSodium;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class UsingSodiumTest extends BtlrServiceTestCase
{
    #[Test]
    public function it_generates_keys_using_sodium_and_saves_them_on_filesystem(): void
    {
        // Fixtures
        $privateKeyFilename = './config/private_key';
        $publicKeyFilename = './config/public_key';

        $keyPair = 'key pair, used as private key, and to generate public key';
        $privateKey = $keyPair;
        $publicKey = 'public key';

        // Dummies
        $fileExists = $this->prophesize(FileExists::class);
        $writeFile = $this->prophesize(WriteFile::class);
        $cryptoBox = $this->prophesize(CryptoBox::class);

        // Stubs & Mocks
        $fileExists->in($privateKeyFilename)
            ->willReturn(false);
        $fileExists->in($publicKeyFilename)
            ->willReturn(false);

        $cryptoBox->keyPair()
            ->willReturn($keyPair);
        $cryptoBox->publicKey($keyPair)
            ->willReturn($publicKey);

        $writeFile->in($privateKeyFilename, $privateKey)
            ->shouldBeCalled();
        $writeFile->in($publicKeyFilename, $publicKey)
            ->shouldBeCalled();

        // Assertion
        $usingSodium = new UsingSodium(
            $fileExists->reveal(),
            $writeFile->reveal(),
            $cryptoBox->reveal(),
        );
        $usingSodium->saveOnFilesystem(
            $privateKeyFilename,
            $publicKeyFilename,
        );
    }

    #[Test]
    public function it_fails_if_private_key_file_already_exists(): void
    {
        // Fixtures
        $privateKeyFilename = './config/private_key';
        $publicKeyFilename = './config/public_key';

        $keyPair = 'key pair, used as private key, and to generate public key';
        $privateKey = $keyPair;
        $publicKey = 'public key';

        // Dummies
        $fileExists = $this->prophesize(FileExists::class);
        $writeFile = $this->prophesize(WriteFile::class);
        $cryptoBox = $this->prophesize(CryptoBox::class);

        // Stubs & Mocks
        $fileExists->in($privateKeyFilename)
            ->willReturn(true);
        $fileExists->in($publicKeyFilename)
            ->shouldNotBeCalled();

        $cryptoBox->keyPair()
            ->shouldNotBeCalled();
        $cryptoBox->publicKey($keyPair)
            ->shouldNotBeCalled();

        $writeFile->in($privateKeyFilename, $privateKey)
            ->shouldNotBeCalled();
        $writeFile->in($publicKeyFilename, $publicKey)
            ->shouldNotBeCalled();

        // Assertion
        $this->expectException(
            FailedDueTo\PrivateKeyFilename\AlreadyExisting::class,
        );

        $usingSodium = new UsingSodium(
            $fileExists->reveal(),
            $writeFile->reveal(),
            $cryptoBox->reveal(),
        );
        $usingSodium->saveOnFilesystem(
            $privateKeyFilename,
            $publicKeyFilename,
        );
    }

    #[Test]
    public function it_fails_if_public_key_file_already_exists(): void
    {
        // Fixtures
        $privateKeyFilename = './config/private_key';
        $publicKeyFilename = './config/public_key';

        $keyPair = 'key pair, used as private key, and to generate public key';
        $privateKey = $keyPair;
        $publicKey = 'public key';

        // Dummies
        $fileExists = $this->prophesize(FileExists::class);
        $writeFile = $this->prophesize(WriteFile::class);
        $cryptoBox = $this->prophesize(CryptoBox::class);

        // Stubs & Mocks
        $fileExists->in($privateKeyFilename)
            ->willReturn(false);
        $fileExists->in($publicKeyFilename)
            ->willReturn(true);

        $cryptoBox->keyPair()
            ->shouldNotBeCalled();
        $cryptoBox->publicKey($keyPair)
            ->shouldNotBeCalled();

        $writeFile->in($privateKeyFilename, $privateKey)
            ->shouldNotBeCalled();
        $writeFile->in($publicKeyFilename, $publicKey)
            ->shouldNotBeCalled();

        // Assertion
        $this->expectException(
            FailedDueTo\PublicKeyFilename\AlreadyExisting::class,
        );

        $usingSodium = new UsingSodium(
            $fileExists->reveal(),
            $writeFile->reveal(),
            $cryptoBox->reveal(),
        );
        $usingSodium->saveOnFilesystem(
            $privateKeyFilename,
            $publicKeyFilename,
        );
    }
}
