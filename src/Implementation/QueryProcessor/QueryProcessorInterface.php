<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Implementation\QueryProcessor;

use Doctrine\ORM\Mapping\MappingException as DoctrineMappingException;
use Doctrine\ORM\Query\QueryException as DoctrineOrmQueryException;


interface QueryProcessorInterface
{
    /**
     * @return object[]
     *
     * @throws DoctrineMappingException
     * @throws DoctrineOrmQueryException
     */
    public function getEntities(): array;
}
