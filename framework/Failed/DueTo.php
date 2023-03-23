<?php

declare(strict_types=1);

namespace Ssc\Btlr\Framework\Failed;

class DueTo extends \DomainException
{
    public const CODE = 422;
    public const TEMPLATE_MESSAGE = 'Invalid %field%: should be %violated_rule%, "%invalid_value%" given';
    public const FIELD = 'field';
    public const VIOLATED_RULE = 'violated_rule';

    final public function __construct(
        string $message = '',
        int $code = 0,
        \Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function with(
        mixed $invalidValue,
    ): self {
        return new static(
            str_replace(
                search: [
                    '%field%',
                    '%violated_rule%',
                    '%invalid_value%',
                ],
                replace: [
                    static::FIELD,
                    static::VIOLATED_RULE,
                    $invalidValue,
                ],
                subject: static::TEMPLATE_MESSAGE,
            ),
            static::CODE,
        );
    }

    public function message(): string
    {
        return $this->getMessage();
    }
}
