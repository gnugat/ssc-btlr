<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Code\Model;

class FullyQualifiedName
{
    /** @var array<string, string> NORMALIZATIONS */
    public const array NORMALIZATIONS = [
        'float' => 'double',
    ];
    public string $fullyQualifiedName;
    public string $name;
    public string $namespace;
    public string $alias = '';

    public function __construct(string $fullyQualifiedName)
    {
        if (isset(self::NORMALIZATIONS[$fullyQualifiedName])) {
            $fullyQualifiedName = self::NORMALIZATIONS[$fullyQualifiedName];
        }
        $namespaces = explode('\\', $fullyQualifiedName);

        $this->name = array_pop($namespaces);
        $this->namespace = implode('\\', $namespaces);
        $this->fullyQualifiedName = trim($fullyQualifiedName, '\\');
    }

    public function getNamespace(): string
    {
        return $this->namespace;
    }

    public function getName(): string
    {
        return '' !== $this->alias ? $this->alias : $this->name;
    }

    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function hasAlias(): bool
    {
        return '' !== $this->alias;
    }

    public function removeAlias(): void
    {
        $this->alias = '';
    }
}
