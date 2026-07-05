<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\Shared;

class AliasedPropertyProvider
{
    /**
     * @param string[] $splitProperty
     */
    public function getAliasedProperty(array $splitProperty, Incrementor $incrementor): string
    {
        $alias = $this->getEntityAlias($splitProperty, $incrementor);
        $lastProperty = $this->getLastProperty($splitProperty);
        return "$alias.$lastProperty";
    }

    /**
     * @param string[] $splitPropertyPath
     */
    private function getLastProperty(array $splitPropertyPath): string
    {
        $pathSize = count($splitPropertyPath);
        return $splitPropertyPath[$pathSize - 1];
    }

    /**
     * @param string[] $splitPropertyPath
     */
    private function getEntityAlias(array $splitPropertyPath, Incrementor $incrementor): string
    {
        $pathSize = count($splitPropertyPath);
        $subSplit = array_slice($splitPropertyPath, 0, $pathSize - 1);
        $subProperty = implode('.', $subSplit);
        return $incrementor->addPropertyPath($subProperty);
    }
}
