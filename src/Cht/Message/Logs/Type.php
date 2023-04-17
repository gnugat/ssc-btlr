<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message\Logs;

class Type
{
    public const USER_PROMPT = [
        'name' => 'user_prompt',
        'directory' => 'messages',
        'priority' => '000',
    ];
    public const AUGMENTED_PROMPT = [
        'name' => 'augmented_prompt',
        'directory' => 'messages',
        'priority' => '500',
    ];
    public const MODEL_COMPLETION = [
        'name' => 'model_completion',
        'directory' => 'messages',
        'priority' => '900',
    ];
    public const SUMMARY = [
        'name' => 'summary',
        'directory' => 'summary',
        'priority' => '500',
    ];

    public const ALL = [
        self::USER_PROMPT['name'] => self::USER_PROMPT,
        self::AUGMENTED_PROMPT['name'] => self::AUGMENTED_PROMPT,
        self::MODEL_COMPLETION['name'] => self::MODEL_COMPLETION,
        self::SUMMARY['name'] => self::SUMMARY,
    ];
}
