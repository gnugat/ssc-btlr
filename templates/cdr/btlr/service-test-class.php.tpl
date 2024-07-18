<?php

declare(strict_types=1);

namespace %fully_qualified_name.namespace%;

use PHPUnit\Framework\Attributes\Test;
use Sut;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class %fully_qualified_name.name% extends BtlrServiceTestCase
{
    #[Test]
    public function it_(): void
    {
        // Fixtures
        $fixture = 42;

        // Dummies
        $dependency = $this->prophesize(Dependency::class);

        // Stubs & Mocks
        $dependency->call($fixture)
            shouldBeCalled()->willReturn(23);

        // Assertion
        $sut = new Sut(
            $dependency->reveal(),
        );
        $this->assertSame(32, $sut->call(
            $fixture,
        ));
    }
}
