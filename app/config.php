<?php

use function DI\create;
use BlogPage\Repositories\ArticleRepositoryInterface;
use BlogPage\Repositories\ArticleRepository;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Libs\SQL\SQLDatabase;
use Libs\Sql\SQLDatabaseInterface;
use Libs\GoogleAuth\GoogleAuthInterface;
use Libs\GoogleAuth\GoogleAuth;

return [
    GoogleAuthInterface::class => create(GoogleAuth::class),
    SQLDatabaseInterface::class => create(SQLDatabase::class),
    ArticleRepositoryInterface::class => DI\autowire(ArticleRepository::class)->constructor(DI\get(SQLDatabaseInterface::class)),

    Environment::class => function () {
        $loader = new FilesystemLoader(__DIR__ . '/../src/BlogPage/Views');
        $twig = new Environment($loader, [
            'debug' => true,
        ]);
        $twig->addExtension(new DebugExtension());
        return $twig;
    },
];