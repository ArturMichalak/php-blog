<?php


namespace BlogPage\Repositories;
use BlogPage\Models\Article;
use Form\Validator;
use Libs\Sql\SQLDatabaseInterface;
use PDO;
use PDOStatement;
use Ramsey\Uuid\Uuid;


class ArticleRepository implements ArticleRepositoryInterface
{
    public function __construct(
        public SQLDatabaseInterface $database
    ) {}

    /**
     * @return Article[]
     */
    public function getArticles(): array
    {
        $articles = $this->prepareQuery('SELECT `id`, `title` FROM `articles`');
        $result = $articles->fetchAll();
        return is_bool($result) ? []: $result;
    }

    public function getArticle(string $id): Article
    {
        $article = $this->prepareQuery('SELECT * FROM `articles` WHERE `id`=:id LIMIT 1', [':id' => $id], Article::class);
        $result = $article->fetch();
        return is_bool($result) ? new Article('', 'Article not found', '') : $result;
    }

    public function sendArticle(string $title, string $content, string $author): void
    {
        $uuid = Uuid::uuid4()->toString();
        $this->prepareQuery(
            'INSERT INTO `articles` (`id`, `title`, `content`) VALUES(:id,:title,:content)',
            [':id' => $uuid, 'title' => $title, 'content' => $content]);
    }

    public function publishArticle(string $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $form = new Validator([
                'title' => ['require', 'trim', 'max_length', 64],
                'content' => ['required', 'max_length' => 5000]
            ]);

            if ($form->validate($_POST))
            {
                echo $form->getValues();
            }
            else
            {
                echo $form->getErrors();
                echo $form->getValues();
            }
        }

    }

    private function prepareQuery(string $query, array $params = [], string $className = ''): PDOStatement
    {
        $prepared = $this->database->getConnection()->prepare($query);
        if ($className != '') $prepared->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, $className);
        $prepared->execute($params);
        return $prepared;
    }
}