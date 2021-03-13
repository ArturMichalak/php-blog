<?php


namespace BlogPage\Repositories;
use BlogPage\Models\Article;
use Libs\GoogleAuth\GoogleAuth;
use Libs\Sql\SQLDatabaseInterface;
use PDO;
use PDOStatement;
use Ramsey\Uuid\Uuid;


class ArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(
        public SQLDatabaseInterface $database,
        public GoogleAuth $client
    ) {}

    /**
     * @param string $author
     * @return Article[]
     */
    public function getArticles(string $author): array
    {
        $articles = $this->prepareQuery('SELECT `article_id`, `title`, `name` as category FROM `articles` INNER JOIN `categories` ON categories.category_id = articles.category_id WHERE `author`=:author OR `status` = 1', [':author' => $author]);
        $result = $articles->fetchAll();
        return is_bool($result) ? []: $result;
    }

    /**
     * @param string $id
     * @return Article
     */
    public function getArticle(string $id): Article
    {
        $article = $this->prepareQuery('SELECT `title`, `content`, `slug`, `name` as category FROM `articles` INNER JOIN `categories` ON categories.category_id = articles.category_id WHERE `article_id`=:id LIMIT 1', [':id' => $id], Article::class);
        $result = $article->fetch();
        return is_bool($result) ? new Article('', 'Article not found', '') : $result;
    }

    /**
     * @param string $title
     * @param string $content
     * @param string $author
     * @param string $slug
     * @param string $category
     */
    public function sendArticle(string $title, string $content, string $author, string $slug, string $category): void
    {
        $uuid = Uuid::uuid4()->toString();
        $categoryUuid = Uuid::uuid4()->toString();
        $this->prepareQuery(
            'CALL p_insert_article(:id, :title, :content, :author, :slug, :category_id, :category)',
            [
                ':id' => $uuid,
                ':title' => $title,
                ':content' => $content,
                ':author' => $author,
                ':slug' => $slug,
                ':category_id' => $categoryUuid,
                ':category' => $category
            ]);
    }

    /**
     * @param string $id
     */
    public function deleteArticle(string $id): void
    {
        $this->prepareQuery(
            'DELETE FROM `articles` WHERE `article_id`=:id LIMIT 1',
            [':id' => $id]);
    }

    /**
     * @param string $id
     * @param string $author
     */
    public function publishArticle(string $id, string $author): void
    {
        $this->prepareQuery(
            'UPDATE `articles` SET `status`=1 WHERE `article_id`=:id AND `author`=:author',
            [':id' => $id, ':author' => $author]);
    }

    /**
     * @param string $id
     * @param string $title
     * @param string $content
     * @param string $slug
     * @param string $author
     */
    public function editArticle(string $id, string $title, string $content, string $slug, string $author): void
    {
        $this->prepareQuery(
            'UPDATE `articles` SET `title`=:title, `content`=:content, `slug`=:slug WHERE `article_id`=:id AND `author`=:author AND `status`=0',
            [':id' => $id, ':title' => $title, ':content' => $content, ':slug' => $slug, ':author' => $author]);
    }

    /**
     * @param string $query
     * @param array $params
     * @param string $className
     * @return PDOStatement
     */
    private function prepareQuery(string $query, array $params = [], string $className = ''): PDOStatement
    {
        $prepared = $this->database->getConnection()->prepare($query);
        if ($className != '') $prepared->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $className);
        $prepared->execute($params);
        return $prepared;
    }
}