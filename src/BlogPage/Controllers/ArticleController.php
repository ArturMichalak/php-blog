<?php


namespace BlogPage\Controllers;

use BlogPage\BusinessLogic\BusinessLogic;
use BlogPage\Repositories\ArticleRepositoryInterface;
use Form\Validator;
use Libs\GoogleAuth\GoogleAuth;
use Twig\Environment;


class ArticleController
{
    public array $info;
    public function __construct(
        public ArticleRepositoryInterface $repository,
        public Environment $twig,
        public GoogleAuth $googleAuth,
    )
    {
        $this->info = $this->googleAuth->info();
    }

    public function item($id): void
    {
        if (BusinessLogic::userNotLogged($this->info, $this->twig)) return;

        $article = $this->repository->getArticle($id);

        echo $this->twig->render('article.twig', [
                'article' => $article,
                'button' => $this->info
            ]);
    }

    public function list(): void
    {
        if (BusinessLogic::userNotLogged($this->info, $this->twig)) return;

        $articles = $this->repository->getArticles($this->info['email']);
        echo  $this->twig->render('articles.twig', [
            'articles' => $articles,
            'button' => $this->info
        ]);
    }

    public function publish($id): void
    {
        if (BusinessLogic::userNotLogged($this->info, $this->twig)) return;
        $this->repository->publishArticle($id, $this->info['email']);
        header('location:/');
    }

    public function add(): void
    {
        if (BusinessLogic::userNotLogged($this->info, $this->twig)) return;

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $form = new Validator([
                'id' => ['required', 'trim', 'max_length' => 36, 'min_length' => 36]
            ]);

            if ($form->validate($_POST))
            {
                $values = $form->getValues();
                $this->repository->publishArticle($values['id'], $this->info['email']);
            }
            header('location:/');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $form = new Validator([
                'title' => ['required', 'trim', 'max_length' => 64],
                'content' => ['required', 'trim', 'max_length' => 5000],
                'slug' => ['required', 'trim', 'max_length' => 64],
                'category' => ['required', 'trim', 'max_length' => 64],
            ]);

            if ($form->validate($_POST))
            {
                $values = $form->getValues();
                $this->repository->sendArticle(
                    $values['title'],
                    $values['content'],
                    $this->info['email'],
                    $values['slug'],
                    $values['category']);
                header('location:/');
                return;
            }
            else
            {
                echo  $this->twig->render('form.twig', [
                    'button' => $this->info,
                    'values' => $form->getValues(),
                    'errors' => $form->getErrors(),
                    'action' => 'nowy',
                ]);
                return;
            }
        }

        echo $this->twig->render('form.twig', [
            'button' => $this->info,
            'action' => 'nowy',
        ]);
    }

    public function delete($id): void
    {
        if (BusinessLogic::userNotLogged($this->info, $this->twig)) return;

        echo $this->twig->render('confirm.twig', [
            'button' => $this->info,
            'id' => $id
        ]);
    }

    public function postDelete(): void
    {
        if (BusinessLogic::userNotLogged($this->info, $this->twig)) return;

        $form = new Validator([
            'id' => ['required', 'trim', 'max_length' => 36, 'min_length' => 36],
        ]);

        if ($form->validate($_POST)) {
            $values = $form->getValues();
            $this->repository->deleteArticle($values['id']);
            header('location:/');
            return;
        }

        $this->delete($_POST['id']);
    }

    public function update($id): void
    {
        if (BusinessLogic::userNotLogged($this->info, $this->twig)) return;

        $article = $this->repository->getArticle($id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $form = new Validator([
                'title' => ['required', 'trim', 'max_length' => 64],
                'content' => ['required', 'trim', 'max_length' => 5000],
                'slug' => ['required', 'trim', 'max_length' => 64]
            ]);

            if ($form->validate($_POST))
            {
                $values = $form->getValues();
                $this->repository->editArticle($id, $values['title'], $values['content'], $values['slug'], $this->info['email']);
                header('location:/');
                return;
            }
            else
            {
                echo  $this->twig->render('form.twig', [
                    'button' => $this->info,
                    'values' => $form->getValues(),
                    'errors' => $form->getErrors(),
                    'action' => 'edytuj/'.$id,
                ]);
                return;
            }
        }

        echo $this->twig->render('form.twig', [
            'button' => $this->info,
            'values' => array('title' => $article->title, 'content' => $article->content, 'slug' => $article->slug, 'category' => $article->category),
            'action' => 'edytuj/'.$id
        ]);
    }
}