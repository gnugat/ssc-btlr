<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Template;

class Replace
{
    /** @param array<string, string> $thoseParameters */
    public function in(string $template, array $thoseParameters): string
    {
        $placeholders = [];
        $values = [];
        foreach ($thoseParameters as $placeholder => $value) {
            $placeholders[] = "%{$placeholder}%";
            $values[] = $value;
        }

        return str_replace($placeholders, $values, $template);
    }
}
