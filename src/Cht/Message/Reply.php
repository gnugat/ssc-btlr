<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Message;

use Ssc\Btlr\Cht\Message\Logs\Type;
use Ssc\Btlr\Cht\Message\Logs\WriteLog;
use Ssc\Btlr\Cht\Message\Memory\Consolidate;
use Ssc\Btlr\Cht\Message\Reply\Augment;
use Ssc\Btlr\Cht\Message\Reply\UsingLlm;

class Reply
{
    public function __construct(
        private Augment $augment,
        private Consolidate $consolidate,
        private UsingLlm $usingLlm,
        private WriteLog $writeLog,
    ) {
    }

    public function to(string $userPrompt, array $withConfig): string
    {
        $this->writeLog->for([
            'entry' => $userPrompt,
        ], Type::USER_PROMPT, $withConfig);

        $augmentedPrompt = $this->augment->the($userPrompt, $withConfig);

        $modelCompletion = $this->usingLlm->complete($augmentedPrompt);
        $this->writeLog->for([
            'entry' => $modelCompletion,
            'llm_engine' => $withConfig['llm_engine'],
        ], Type::MODEL_COMPLETION, $withConfig);

        $this->consolidate->memories($withConfig);

        return $modelCompletion;
    }
}
