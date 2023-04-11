<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Reply\UsingLlm;

interface Engine
{
    public function complete(string $prompt): string;
}
