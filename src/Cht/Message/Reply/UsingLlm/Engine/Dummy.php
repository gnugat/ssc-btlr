<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Reply\UsingLlm\Engine;

use Ssc\Btlr\Cht\Message\Reply\UsingLlm\Engine;

class Dummy implements Engine
{
    public function complete(string $prompt): string
    {
        return "I'm sorry, dev. I'm afraid I can't do that.";
    }
}
