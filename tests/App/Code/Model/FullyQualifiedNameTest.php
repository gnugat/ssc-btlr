<?php

declare(strict_types=1);

namespace tests\Ssc\Btlr\App\Code\Model;

use Ssc\Btlr\App\Code\Model\FullyQualifiedName;
use tests\Ssc\Btlr\AppTest\BtlrServiceTestCase;

class FullyQualifiedNameTest extends BtlrServiceTestCase
{
    /**
     * @test
     */
    public function it_has_fully_qualified_classname(): void
    {
        $fullyQualifiedName = new FullyQualifiedName('Vendor\Project\MyClass');

        self::assertSame('Vendor\Project\MyClass', $fullyQualifiedName->fullyQualifiedName);
    }

    /**
     * @test
     */
    public function it_has_name(): void
    {
        $fullyQualifiedName = new FullyQualifiedName('Vendor\Project\MyClass');

        self::assertSame('MyClass', $fullyQualifiedName->getName());
    }

    /**
     * @test
     */
    public function it_has_namespace(): void
    {
        $fullyQualifiedName = new FullyQualifiedName('Vendor\Project\MyClass');

        self::assertSame('Vendor\Project', $fullyQualifiedName->getNamespace());
    }

    /**
     * @test
     */
    public function it_can_have_an_alias(): void
    {
        $fullyQualifiedName = new FullyQualifiedName('Vendor\Project\MyClass');

        self::assertSame(false, $fullyQualifiedName->hasAlias());
        self::assertSame('MyClass', $fullyQualifiedName->getName());

        $fullyQualifiedName->setAlias('MyAlias');
        self::assertSame(true, $fullyQualifiedName->hasAlias());
        self::assertSame('MyAlias', $fullyQualifiedName->getName());

        $fullyQualifiedName->removeAlias();
        self::assertSame(false, $fullyQualifiedName->hasAlias());
        self::assertSame('MyClass', $fullyQualifiedName->getName());
    }

    /**
     * @test
     */
    public function it_normalizes_float_name(): void
    {
        $fullyQualifiedName = new FullyQualifiedName('float');

        self::assertSame('double', $fullyQualifiedName->fullyQualifiedName);
    }
}
