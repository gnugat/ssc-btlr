<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Augment\UsingLlm\Model;

interface Engine
{
    public function complete(string $prompt): string;
}
