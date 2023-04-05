<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Cht\Augment\UsingLlm\Augment;

use Ssc\Btlr\Cht\Augment\UsingLlm\Augment\GetLastMessages;
use Ssc\Btlr\Cht\Augment\UsingLlm\Log\Source;
use Ssc\Btlr\Framework\Filesystem\FindFiles;
use Ssc\Btlr\Framework\Filesystem\ReadFile;
use Ssc\Btlr\TestFramework\BtlrServiceTestCase;

class GetLastMessagesTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_gets_last_messages(): void
    {
        // Fixtures
        $lastMessagesFilename = './var/cht/logs/last_messages';
        $userPromptLogFilename = './var/cht/logs/last_messages/000_user_prompt.json';
        $augmentedPromptLogFilename = './var/cht/logs/last_messages/500_augmented_prompt.json';
        $modelCompletionLogFilename = './var/cht/logs/last_messages/900_model_completion.json';
        $logFilenames = [
            $userPromptLogFilename,
            $augmentedPromptLogFilename,
            $modelCompletionLogFilename,
        ];
        $userPromptLogParameters = [
            'entry' => 'Do you read me?',
            'time' => '1968-04-02T18:40:23+00:00',
            'source' => Source::USER_PROMPT,
        ];
        $userPromptLogContent = json_encode($userPromptLogParameters);
        $augmentedPromptLogParameters = [
            'entry' => "USER ({$userPromptLogParameters['time']}):"
                ." {$userPromptLogParameters['entry']}\n"
                .'BTLR:',
            'time' => '1968-04-02T18:40:23+00:00',
            'source' => Source::AUGMENTED_PROMPT,
        ];
        $augmentedPromptLogContent = json_encode($augmentedPromptLogParameters);
        $modelCompletionLogParameters = [
            'entry' => 'Affirmative dev, I read you',
            'time' => '1968-04-02T18:40:42+00:00',
            'source' => Source::MODEL_COMPLETION,
        ];
        $modelCompletionLogContent = json_encode($modelCompletionLogParameters);
        $lastMessages = "USER ({$userPromptLogParameters['time']}):"
                ." {$userPromptLogParameters['entry']}\n"
                ."BTLR ({$modelCompletionLogParameters['time']}):"
                ." {$modelCompletionLogParameters['entry']}\n";

        // Dummies
        $findFiles = $this->prophesize(FindFiles::class);
        $readFile = $this->prophesize(ReadFile::class);

        // Stubs & Mocks
        $findFiles->in($lastMessagesFilename)
            ->willReturn($logFilenames);
        $readFile->in($userPromptLogFilename)
            ->willReturn($userPromptLogContent);
        $readFile->in($augmentedPromptLogFilename)
            ->willReturn($augmentedPromptLogContent);
        $readFile->in($modelCompletionLogFilename)
            ->willReturn($modelCompletionLogContent);

        // Assertion
        $getLastMessages = new GetLastMessages(
            $findFiles->reveal(),
            $readFile->reveal(),
        );
        $actualLastMessages = $getLastMessages->from(
            $lastMessagesFilename,
        );
        self::assertSame($lastMessages, $actualLastMessages);
    }
}
