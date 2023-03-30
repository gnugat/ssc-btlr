<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Augment;

use Ssc\Btlr\Cht\Augment\UsingLlm\Augment;
use Ssc\Btlr\Cht\Augment\UsingLlm\Log;
use Ssc\Btlr\Cht\Augment\UsingLlm\Log\Source;
use Ssc\Btlr\Cht\Augment\UsingLlm\Model;

class UsingLlm
{
    public function __construct(
        private Augment $augment,
        private Log $log,
        private Model $model,
    ) {
    }

    public function complete(
        string $userPrompt,
        array $withConfig,
    ): string {
        $this->log->entry($userPrompt, $withConfig, Source::USER_PROMPT);

        $augmentedPrompt = $this->augment->the($userPrompt, $withConfig);

        $modelCompletion = $this->model->complete($augmentedPrompt);
        $this->log->entry($modelCompletion, $withConfig, Source::MODEL_COMPLETION);

        return $modelCompletion;
    }
}
