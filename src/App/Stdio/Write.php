<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Stdio;

use Ssc\Btlr\App\Stdio\Write\WithStyle;
use Symfony\Component\Console\Output\OutputInterface;

class Write
{
    public const string DEFAULT_STYLE = WithStyle::AS_REGULAR_TEXT;
    /** @var array{} USE_RECOMMENDED_STYLES */
    private const array USE_RECOMMENDED_STYLES = [];

    /** @param array<string, WithStyle> $configuredStyles */
    public function __construct(
        private array $configuredStyles = self::USE_RECOMMENDED_STYLES,
        private string $defaultStyle = WithStyle::AS_REGULAR_TEXT,
    ) {
        if (self::USE_RECOMMENDED_STYLES === $configuredStyles) {
            $this->configuredStyles = [
                WithStyle::AS_COMMAND => new WithStyle\AsCommand(),
                WithStyle::AS_COMMAND_TITLE => new WithStyle\AsCommandTitle(),
                WithStyle::AS_ERROR_BLOCK => new WithStyle\AsErrorBlock(),
                WithStyle::AS_INSTRUCTION => new WithStyle\AsInstruction(),
                WithStyle::AS_REGULAR_TEXT => new WithStyle\AsRegularText(),
                WithStyle::AS_SECTION_TITLE => new WithStyle\AsSectionTitle(),
                WithStyle::AS_SUCCESS_BLOCK => new WithStyle\AsSuccessBlock(),
            ];
        }
        if (false === isset($this->configuredStyles[$defaultStyle])) {
            $this->defaultStyle = WithStyle::AS_REGULAR_TEXT;
            $this->configuredStyles[$this->defaultStyle] = new WithStyle\AsRegularText();
        }
    }

    public function the(
        string $message,
        string $withStyle,
        OutputInterface $onOutput,
    ): void {
        $stylishly = $this->configuredStyles[$this->defaultStyle];
        foreach ($this->configuredStyles as $styleName => $configuredStyle) {
            if ($withStyle === $styleName) {
                $stylishly = $configuredStyle;

                break;
            }
        }
        $stylishly->write($message, $onOutput);
    }
}
