<?php


namespace BlogPage\Controllers;

use BlogPage\Repositories\ArticleRepositoryInterface;
use Libs\GoogleAuth\GoogleAuth;
use Twig\Environment;


class ArticleController
{
    private GoogleAuth $google;
    public array $googleButton;
    public function __construct(
        public ArticleRepositoryInterface $repository,
        public Environment $twig,

    ) {
        $this->google = new GoogleAuth();
        $this->googleButton = $this->google->info();
    }

    public function item($id): void
    {
        if (!array_key_exists('name', $this->googleButton))
        {
            echo $this->twig->render('nologged.twig', ['button' => $this->googleButton]);
            return;
        }

        $article = $this->repository->getArticle($id);

        echo $this->twig->render('article.twig', [
                'article' => $article,
                'button' => $this->googleButton
            ]);
    }

    public function list(): void
    {
        if (!array_key_exists('name', $this->googleButton))
        {
            echo $this->twig->render('nologged.twig', ['button' => $this->googleButton]);
            return;
        }
        $articles = $this->repository->getArticles();
        echo  $this->twig->render('articles.twig', [
            'articles' => $articles,
            'button' => $this->googleButton
        ]);
    }

    public function add(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {

        }
    }
}