<?php

declare(strict_types=1);

namespace Ssc\Btlr\Cdr\Generate;

use Ssc\Btlr\App\Filesystem\ReadFile;
use Ssc\Btlr\App\Template\Replace;

class PromptFromTemplate
{
    public function __construct(
        private ReadFile $readFile,
        private Replace $replace,
    ) {
    }

    public function using(
        string $promptTemplateFilename,
        string $testClassCodeExampleFilename,
        string $correspondingClassCodeExampleFilename,
        string $testClassCodeFilename,
    ): string {
        $promptTemplateContent = $this->readFile->in($promptTemplateFilename);

        $testClassCodeExampleContent = $this->readFile->in($testClassCodeExampleFilename);
        $correspondingClassCodeExampleContent = $this->readFile->in($correspondingClassCodeExampleFilename);
        $testClassCodeContent = $this->readFile->in($testClassCodeFilename);

        $prompt = $this->replace->in($promptTemplateContent, [
            'test_class_code_example' => $testClassCodeExampleContent,
            'corresponding_class_code_example' => $correspondingClassCodeExampleContent,
            'test_class_code' => $testClassCodeContent,
        ]);

        return $prompt;
    }
}
