<?php

declare(strict_types=1);

namespace Ssc\Btlr\App\Code\Model;

/**
 * @api
 */
class FullyQualifiedName
{
    public const NORMALIZATIONS = [
        'float' => 'double',
    ];
    public string $fullyQualifiedName;
    public string $name;
    public string $namespace;
    public ?string $alias = null;

    /**
     * @api
     */
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

    /**
     * @api
     */
    public function getNamespace(): string
    {
        return $this->namespace;
    }

    /**
     * @api
     */
    public function getName(): string
    {
        return $this->alias ?? $this->name;
    }

    /**
     * @api
     */
    public function setAlias(string $alias): self
    {
        $this->alias = $alias;

        return $this;
    }

    public function hasAlias(): bool
    {
        return null !== $this->alias;
    }

    /**
     * @api
     */
    public function removeAlias(): void
    {
        $this->alias = null;
    }
}
