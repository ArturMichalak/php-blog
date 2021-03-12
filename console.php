<?php

use BlogPage\Repositories\ArticleRepositoryInterface;
use Symfony\Component\Console\Output\OutputInterface;

$container = require __DIR__ . '/app/bootstrap.php';
$app = new Silly\Application();
$app->useContainer($container, $injectWithTypeHint = true);
$app->command('articles', function (OutputInterface $output, ArticleRepositoryInterface $repository) {
    $output->writeln('<comment>Here are the articles in the blog:</comment>');

    $articles = $repository->getArticles();

    foreach ($articles as $article) {
        $output->writeln(sprintf(
            'Article #%d: <info>%s</info>',
            $article->getId(),
            $article->getTitle()
        ));
    }
});
$app->command('article [id]', 'BlogPage\Commands\ArticleLoadCommand');
$app->run();
