<?php

declare(strict_types=1);

namespace Ssc\Btlr\Framework;

use Ssc\Btlr\Framework\Stdio\Write;
use Ssc\Btlr\Framework\Stdio\Write\WithStyle;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class Stdio
{
    public const EMPTY_LINE = '';

    public function __construct(
        private InputInterface $input,
        private OutputInterface $output,
        private Write $write = new Write(),
        private QuestionHelper $questionHelper = new QuestionHelper(),
    ) {
    }

    public function ask(string $question): string
    {
        $this->write->the(
            '(Multiline mode enabled, to submit when finished typing, hit ENTER then CTRL-D)',
            WithStyle::AS_INSTRUCTION,
            $this->output,
        );
        $this->write->the(
            self::EMPTY_LINE,
            WithStyle::AS_REGULAR_TEXT,
            $this->output,
        );

        return $this->questionHelper->ask(
            $this->input,
            $this->output,
            (new Question("<fg=green>{$question}</>"))->setMultiline(true),
        );
    }

    public function write(
        string $message,
        string $withStyle = WithStyle::AS_REGULAR_TEXT,
    ): void {
        $this->write->the($message, $withStyle, onOutput: $this->output);
    }
}
