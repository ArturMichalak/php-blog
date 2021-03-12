<?php

namespace BlogPage\Commands;

use BlogPage\Repositories\ArticleRepositoryInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ArticleLoadCommand
{
    public function __construct(
        public ArticleRepositoryInterface $repository) {}

    public function __invoke($id, OutputInterface $output): void
    {
        $article = $this->repository->getArticle($id);

        $output->writeln('<info>' . $article->getTitle() . '</info>');
        $output->writeln($article->getContent());
    }
}