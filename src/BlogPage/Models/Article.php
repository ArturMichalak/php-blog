<?php

namespace BlogPage\Models;

class Article
{
    public function __construct(
        public string $id = '',
        public string $title = '',
        public string $content = ''
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}