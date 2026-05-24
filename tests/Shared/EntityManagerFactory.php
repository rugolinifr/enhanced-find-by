<?php

declare(strict_types=1);

namespace Rugolinifr\EnhancedFindBy\Tests\Shared;

use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Tools\DsnParser;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;

class EntityManagerFactory
{
    public static function createEntityManager(): EntityManagerInterface
    {
        $config = ORMSetup::createAttributeMetadataConfig(
            paths: ['./tests/Entity'],
            isDevMode: true,
        );
        $config->setProxyDir('./proxy/');
        $config->setProxyNamespace('Rugolinifr\EnhancedFindBy\Proxy');
        $url = 'pdo-mysql://root:password@mysql/find_by?charset=utf8mb4';
        $dsnParser = new DsnParser();
        $connectionParams = $dsnParser->parse($url);
        $connection = DriverManager::getConnection($connectionParams, $config);
        return new EntityManager($connection, $config);
    }
}
