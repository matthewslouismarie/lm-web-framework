<?php

declare(strict_types=1);

namespace LM\WebFramework\DataStructures;

/**
 * @todo Should go in web namespace.
 */
final class Page
{
    public function __construct(
        private ?Page $parent,
        private string $name,
        private string $url,
        private bool $isIndexed = true,
        private bool $isPartOfHierarchy = true,
    ) {
    }

    public function getParent(): ?Page
    {
        return $this->parent;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function isIndexed(): bool
    {
        return $this->isIndexed;
    }

    public function isPartOfHierarchy(): bool
    {
        return $this->isPartOfHierarchy;
    }
}
