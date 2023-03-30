<?php

declare(strict_types=1);

namespace Ssc\Btlr\Framework\Identifier;

use Ramsey\Uuid\Uuid as RamseyUuid;

class Uuid
{
    public function make(): string
    {
        return (string) RamseyUuid::uuid4();
    }
}
