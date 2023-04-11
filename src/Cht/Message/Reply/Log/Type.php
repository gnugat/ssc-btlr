<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Reply\Log;

class Type
{
    public const USER_PROMPT = [
        'name' => 'user_prompt',
        'directory' => 'last_messages',
        'priority' => '000',
    ];
    public const AUGMENTED_PROMPT = [
        'name' => 'augmented_prompt',
        'directory' => 'last_messages',
        'priority' => '500',
    ];
    public const MODEL_COMPLETION = [
        'name' => 'model_completion',
        'directory' => 'last_messages',
        'priority' => '900',
    ];
}
