<?php


namespace BlogPage\Repositories;


use BlogPage\Models\Article;

interface ArticleRepositoryInterface
{
    /**
     * @return Article[]
     */
    public function getArticles(): array;

    public function getArticle(String $id): Article;

    public function sendArticle(string $title, string $content, string $author): void;

    public function publishArticle(string $id): void;
}