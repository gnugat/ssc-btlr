<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Time;

class Clock
{
    public function inFormat(string $format): string
    {
        return (new \DateTime())->format($format);
    }
}
