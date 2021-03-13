<?php


namespace BlogPage\Repositories;


use BlogPage\Models\Article;

interface ArticleRepositoryInterface
{
    /**
     * @param string $author
     * @return Article[]
     */
    public function getArticles(string $author): array;

    public function getArticle(String $id): Article;

    public function sendArticle(string $title, string $content, string $author, string $slug, string $category): void;

    public function editArticle(String $id, string $title, string $content, string $slug, string $author): void;

    public function deleteArticle(string $id): void;

    public function publishArticle(string $id, string $author): void;
}