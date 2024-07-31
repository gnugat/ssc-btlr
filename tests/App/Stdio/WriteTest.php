<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\App\Stdio;

use PHPUnit\Framework\Attributes\Test;
use Ssc\Btlr\App\Stdio\Write;
use Ssc\Btlr\App\Stdio\Write\WithStyle;
use Symfony\Component\Console\Output\OutputInterface;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class WriteTest extends BtlrServiceTestCase
{
    #[Test]
    public function it_uses_given_style_to_writes_the_message_on_output(): void
    {
        // Dummies
        $output = $this->prophesize(OutputInterface::class);
        $withStyleAsSectionTitle = $this->prophesize(WithStyle\AsSectionTitle::class);
        $withStyleAsCommand = $this->prophesize(WithStyle\AsCommand::class);
        $withStyleAsRegularText = $this->prophesize(WithStyle\AsRegularText::class);

        // Fixtures
        $message = './btlr --list-commands';
        $withStyle = WithStyle::AS_COMMAND;
        $configuredStyles = [
            WithStyle::AS_SECTION_TITLE => $withStyleAsSectionTitle->reveal(),
            WithStyle::AS_COMMAND => $withStyleAsCommand->reveal(),
            WithStyle::AS_REGULAR_TEXT => $withStyleAsRegularText->reveal(),
        ];
        $defaultStyle = WithStyle::AS_REGULAR_TEXT;

        // Stubs & Mocks
        $withStyleAsSectionTitle->write($message, onOutput: $output)
            ->shouldNotBeCalled();
        $withStyleAsCommand->write($message, onOutput: $output)
            ->shouldBeCalled();
        $withStyleAsRegularText->write($message, onOutput: $output)
            ->shouldNotBeCalled();

        // Assertion
        $write = new Write(
            $configuredStyles,
            $defaultStyle,
        );
        $write->the(
            $message,
            $withStyle,
            onOutput: $output->reveal(),
        );
    }

    #[Test]
    public function it_uses_default_style_if_given_one_is_not_in_the_configured_ones(): void
    {
        // Dummies
        $output = $this->prophesize(OutputInterface::class);
        $withStyleAsSectionTitle = $this->prophesize(WithStyle\AsSectionTitle::class);
        $withStyleAsRegularText = $this->prophesize(WithStyle\AsRegularText::class);
        $withStyleAsCommand = $this->prophesize(WithStyle\AsCommand::class);

        // Fixtures
        $message = './btlr --list-commands';
        $withStyle = 'Style NOT configured';
        $configuredStyles = [
            WithStyle::AS_SECTION_TITLE => $withStyleAsSectionTitle->reveal(),
            WithStyle::AS_REGULAR_TEXT => $withStyleAsRegularText->reveal(),
            WithStyle::AS_COMMAND => $withStyleAsCommand->reveal(),
        ];
        $defaultStyle = WithStyle::AS_REGULAR_TEXT;

        // Stubs & Mocks
        $withStyleAsSectionTitle->write($message, onOutput: $output)
            ->shouldNotBeCalled();
        $withStyleAsRegularText->write($message, onOutput: $output)
            ->shouldBeCalled();
        $withStyleAsCommand->write($message, onOutput: $output)
            ->shouldNotBeCalled();

        // Assertion
        $write = new Write(
            $configuredStyles,
            $defaultStyle,
        );
        $write->the(
            $message,
            $withStyle,
            onOutput: $output->reveal(),
        );
    }
}
