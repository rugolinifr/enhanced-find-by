<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Shared;

use Rugolinifr\EnhancedFindBy\Implementation\Shared\StrictFunction as SF;

class Incrementor
{
    /** @var array<string, mixed> with [ :parameterName => parameterValue ] */
    private array $parameters = [];
    /** @var array<string, string> with [ :entityName => propertyPath ]  */
    private array $entities = [];

    /**
     * Adds the given parameter value to this incrementor's collection, then returns is newly created name.
     */
    public function addParameter(mixed $value): string
    {
        $parameterName = array_find_key($this->parameters, fn(mixed $target) => $target === $value);
        if ($parameterName === null) {
            $parameterName = ':p' . count($this->parameters);
            $this->parameters[$parameterName] = $value;
        }
        return $parameterName;
    }

    /**
     * @return array<string, mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * Adds the given property path to this incrementor collection then returns is newly created DQL alias.
     */
    public function addPropertyPath(string $propertyPath): string
    {
        return $this->addPropertyPathRecursively($propertyPath);
    }

    private function addPropertyPathRecursively(string $propertyPath): string
    {
        if (empty($propertyPath)) {
            return 'e0';
        }
        $entityName = array_find_key($this->entities, fn(string $target) => $target === $propertyPath);
        if ($entityName === null) {
            $previousPropertyPath = SF::preg_replace('/\.?[a-zA-Z0-9_]+$/', '', $propertyPath);
            $this->addPropertyPathRecursively($previousPropertyPath);
            $entityName = 'e' . count($this->entities) + 1;
            $this->entities[$entityName] = $propertyPath;
        }
        return $entityName;
    }
}
