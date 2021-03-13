### BLOG NA SZYBKO W PHP

####Szybki projekt sprawdzający moje kompetencje w dawno porzuconym języku.


```mysql
-- Drop Tables
DROP TABLE Articles;
DROP TABLE Categories;

-- Categories Table
CREATE TABLE Categories (
    category_id CHAR(36) PRIMARY KEY,
    name VARCHAR(64) NOT NULL UNIQUE 
);

-- Articles Table
CREATE TABLE Articles (
    article_id CHAR(36) PRIMARY KEY,
    title VARCHAR(64) NOT NULL,
    content VARCHAR(5000) NOT NULL,
    author VARCHAR(64) NOT NULL,
    slug VARCHAR(54) NOT NULL UNIQUE,
    category_id CHAR(64) NOT NULL,
    status INT(2) UNSIGNED NOT NULL,
    FULLTEXT (content),
    INDEX (author, category_id),
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
);

-- Insert Article Procedure
DROP PROCEDURE IF EXISTS p_insert_article;
CREATE PROCEDURE p_insert_article(
    article_id CHAR(36),
    title VARCHAR(64),
    content VARCHAR(5000),
    author VARCHAR(64),
    slug VARCHAR(54),
    category_id CHAR(36),
    category VARCHAR(64)
)
BEGIN
    DECLARE categoryId CHAR(64) DEFAULT '';
    START TRANSACTION;
    SELECT category_id INTO categoryId FROM Categories WHERE category = `name`;
    IF categoryId = '' THEN
        INSERT INTO Categories VALUES(category_id, category);
        SET categoryId = category_id; 
    END IF;
    INSERT INTO Articles VALUES (article_id, title, content, author, slug, categoryId, 0);
    COMMIT;
END;
```

Pobranie konfiguracji OAuth z konsoli google i wklejenie w credentials.json w katalogu głównym.

uruchomienie lokalnie
```
composer install
composer serve
```

