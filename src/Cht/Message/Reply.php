<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message;

use Ssc\Btlr\Cht\Message\DataCollection\Type;
use Ssc\Btlr\Cht\Message\DataCollection\WriteLog;
use Ssc\Btlr\Cht\Message\Reply\Augment;
use Ssc\Btlr\Cht\Message\Reply\UsingLlm;

class Reply
{
    public function __construct(
        private Augment $augment,
        private UsingLlm $usingLlm,
        private WriteLog $writeLog,
    ) {
    }

    public function to(string $userPrompt, array $withConfig): string
    {
        $this->writeLog->for($userPrompt, $withConfig, Type::USER_PROMPT);

        $augmentedPrompt = $this->augment->the($userPrompt, $withConfig);
        $this->writeLog->for($augmentedPrompt, $withConfig, Type::AUGMENTED_PROMPT);

        $modelCompletion = $this->usingLlm->complete($augmentedPrompt);
        $this->writeLog->for($modelCompletion, $withConfig, Type::MODEL_COMPLETION);

        return $modelCompletion;
    }
}
