<?php

declare(strict_types=1);

namespace Ssc\Btlr\Framework\Sodium;

class CryptoBox
{
    public function keyPair(): string
    {
        return sodium_crypto_box_keypair();
    }

    public function publicKey(string $keyPair): string
    {
        return sodium_crypto_box_publickey($keyPair);
    }
}
