<?php

declare(strict_types=1);

use DI\ContainerBuilder;
use App\Domain\User\UserRepository;
use App\Infrastructure\Repository\SqlRepository\SqlInterface;
use App\Infrastructure\Persistence\User\InMemoryUserRepository;
use App\Infrastructure\Repository\SqlRepository\SqlRepository;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        UserRepository::class => \DI\autowire(InMemoryUserRepository::class),
        SqlInterface::class => \DI\autowire(SqlRepository::class),


    ]);
};
