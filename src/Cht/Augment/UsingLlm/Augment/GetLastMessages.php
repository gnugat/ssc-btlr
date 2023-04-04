<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cht\Augment\UsingLlm\Augment;

use Ssc\Btlr\Cht\Augment\UsingLlm\Log\Source;
use Ssc\Btlr\Framework\Filesystem\FindFiles;
use Ssc\Btlr\Framework\Filesystem\ReadFile;

class GetLastMessages
{
    public function __construct(
        private FindFiles $findFiles,
        private ReadFile $readFile,
    ) {
    }

    public function from(string $lastMessagesFilename): string
    {
        $lastMessages = '';
        $logFilenames = $this->findFiles->in($lastMessagesFilename);
        foreach ($logFilenames as $logFilename) {
            $logParameters = json_decode($this->readFile->in($logFilename), true);
            if (Source::USER_PROMPT === $logParameters['source']) {
                $lastMessages .= "USER: {$logParameters['entry']}\n";
            }
            if (Source::MODEL_COMPLETION === $logParameters['source']) {
                $lastMessages .= "BTLR: {$logParameters['entry']}\n";
            }
        }

        return $lastMessages;
    }
}
