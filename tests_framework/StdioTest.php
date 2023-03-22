<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\Framework;

use Ssc\Btlr\Framework\Stdio;
use Ssc\Btlr\Framework\Stdio\Write;
use Ssc\Btlr\Framework\Stdio\Write\WithStyle;
use Ssc\Btlr\TestFramework\BtlrServiceTestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StdioTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_is_a_shortcut_for_stdio_write(): void
    {
        // Fixtures
        $message = 'Hello world';
        $withStyle = WithStyle::AS_REGULAR_TEXT;

        // Dummies
        $input = $this->prophesize(InputInterface::class);
        $output = $this->prophesize(OutputInterface::class);
        $write = $this->prophesize(Write::class);

        // Stubs & Mocks
        $write->the($message, $withStyle, onOuptut: $output)
            ->shouldBeCalled();

        // Assertion
        $stdio = new Stdio(
            $input->reveal(),
            $output->reveal(),
            $write->reveal(),
        );
        $stdio->write(
            $message,
            $withStyle,
        );
    }
}
