<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Lck;

use Ssc\Btlr\Lck\GenerateKeys;
use tests\Ssc\Btlr\AppTest\BtlrCliTestCase;

class GenerateKeysTest extends BtlrCliTestCase
{
    /**
     * @test
     */
    public function it_generates_keys(): void
    {
        $forTests = __DIR__.'/../../var/tests';
        $input = [
            GenerateKeys::NAME,
            '--private-key-filename' => "{$forTests}/config/lck/private_decrypting_key",
            '--public-key-filename' => "{$forTests}/config/lck/public_encrypting_key",
        ];

        $statusCode = $this->app->run($input);

        $this->shouldSucceed($statusCode);
    }

    /**
     * @test
     */
    public function it_fails_if_keys_already_exist(): void
    {
        $forTests = __DIR__.'/../../var/tests';
        $input = [
            GenerateKeys::NAME,
            '--private-key-filename' => "{$forTests}/config/lck/private_decrypting_key",
            '--public-key-filename' => "{$forTests}/config/lck/public_encrypting_key",
        ];

        $statusCode = $this->app->run($input);

        $this->shouldFail($statusCode);
    }
}
