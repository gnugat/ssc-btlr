<?php

declare(strict_types=1);

namespace %fully_qualified_name.namespace%;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sut;

class %fully_qualified_name.name% extends TestCase
{
    use ProphecyTrait;

    #[Test]
    public function it_(): void
    {
        // Fixtures - Input parameters
        $fixture = 42;

        // Dummies - Service dependencies
        $dependency = $this->prophesize(Dependency::class);

        // Stubs & Mocks - Specification
        $dependency->call($fixture)
            shouldBeCalled()->willReturn(23);

        // Assertion - Usage
        $sut = new Sut(
            $dependency->reveal(),
        );
        $this->assertSame(32, $sut->call(
            $fixture,
        ));
    }
}
