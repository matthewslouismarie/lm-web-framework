<?php

declare(strict_types=1);

namespace LM\WebFramework\Model\Type;

use InvalidArgumentException;

final class EntityModel extends AbstractModel
{
    /**
     * @param string $prefix The model slug identifier.
     * @param IModel[] $properties An associative list of properties.
     * @param bool $isNullable Whether the entity is nullable.
     */
    public function __construct(
        private string $identifier,
        private array $properties,
        private string $idKey = 'id',
        bool $isNullable = false,
    ) {
        if (!key_exists($idKey, $properties)) {
            throw new InvalidArgumentException('Specified ID key is not among the model’s properties.');
        }
        parent::__construct($isNullable);
    }

    /**
     * @return string An identifier for the model.
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * @return string The key of the model property that serves as its
     * primary key.
     */
    public function getIdKey(): string
    {
        return $this->idKey;
    }
 
    /**
     * @return \LM\WebFramework\Model\Type\IModel[] An associative array of
     * properties. This guarantees each property has a unique key in the context
     * of the model.
     */
    public function getProperties(): array
    {
        return $this->properties;
    }

    public function addProperty(string $key, IModel $model): self
    {
        if (key_exists($key, $this->getProperties())) {
            throw new InvalidArgumentException('A property already exists with that key.');
        }
        return new self(
            $this->getIdentifier(),
            [$key => $model] + $this->getProperties(),
            $this->getIdKey(),
            $this->isNullable(),
        );
    }

    public function removeProperty(string $keyToRemove): self
    {
        if (key_exists($keyToRemove, $this->getProperties())) {
            return new self(
                $this->getIdentifier(),
                array_filter($this->getProperties(), fn ($key) => $key !== $keyToRemove, ARRAY_FILTER_USE_KEY),
                $this->getIdKey(),
                $this->isNullable(),
            );
        }
        throw new InvalidArgumentException('No property with that key exists.');
    }

    public function prune(array $propertiesToKeep): self
    {
        return new self(
            $this->getIdentifier(),
            array_filter($this->getProperties(), fn ($key) => in_array($key, $propertiesToKeep, strict: true), ARRAY_FILTER_USE_KEY),
            $this->getIdKey(),
            $this->isNullable(),
        );
    }
}