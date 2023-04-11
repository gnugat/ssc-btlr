<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message;

use Ssc\Btlr\Cht\Message\Reply\Augment;
use Ssc\Btlr\Cht\Message\Reply\Log;
use Ssc\Btlr\Cht\Message\Reply\Log\Type;
use Ssc\Btlr\Cht\Message\Reply\UsingLlm;

class Reply
{
    public function __construct(
        private Augment $augment,
        private Log $log,
        private UsingLlm $usingLlm,
    ) {
    }

    public function to(string $userPrompt, array $withConfig): string
    {
        $this->log->entry($userPrompt, $withConfig, Type::USER_PROMPT);

        $augmentedPrompt = $this->augment->the($userPrompt, $withConfig);
        $this->log->entry($augmentedPrompt, $withConfig, Type::AUGMENTED_PROMPT);

        $modelCompletion = $this->usingLlm->complete($augmentedPrompt);
        $this->log->entry($modelCompletion, $withConfig, Type::MODEL_COMPLETION);

        return $modelCompletion;
    }
}
