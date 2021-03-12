<?php

use function DI\create;
use BlogPage\Repositories\ArticleRepositoryInterface;
use BlogPage\Repositories\ArticleRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Libs\SQL\SQLDatabase;
use Libs\Sql\SQLDatabaseInterface;

return [
    // Bind an interface to an implementation
    SQLDatabaseInterface::class => create(SQLDatabase::class),
    ArticleRepositoryInterface::class => DI\autowire(ArticleRepository::class)->constructor(DI\get(SQLDatabaseInterface::class)),

    // Configure Twig
    Environment::class => function () {
        $loader = new FilesystemLoader(__DIR__ . '/../src/BlogPage/Views');
        return new Environment($loader);
    },
];