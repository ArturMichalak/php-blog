### BLOG NA SZYBKO W PHP

####Szybki projekt sprawdzający moje kompetencje w dawno porzuconym języku.

Tabela
```mysql
CREATE TABLE articles (
    id CHAR(36) PRIMARY KEY,
    title VARCHAR(64) NOT NULL,
    content VARCHAR(5000) NOT NULL
);
```

Pobranie konfiguracji OAuth z konsoli google i wklejenie w credentials.json w katalogu głównym.

uruchomienie lokalnie
```
composer install
composer serve
```

