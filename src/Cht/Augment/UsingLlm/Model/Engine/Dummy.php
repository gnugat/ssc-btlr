<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Augment\UsingLlm\Model\Engine;

use Ssc\Btlr\Cht\Augment\UsingLlm\Model\Engine;

class Dummy implements Engine
{
    public function complete(string $prompt): string
    {
        return "I'm sorry, dev. I'm afraid I can't do that.";
    }
}
