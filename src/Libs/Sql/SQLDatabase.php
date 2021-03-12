<?php

namespace Libs\Sql;

use PDO;

class SQLDatabase implements SQLDatabaseInterface
{
    private PDO $connection;

    public function __construct(
        private $dbHost = 'localhost',
        private $dbUser = 'root',
        private $dbPass = 'zaq1@WSX!23',
        private $dbName = 'blog'
    )
    {
        $this->connection = new PDO("mysql:host=".$dbHost.";dbname=".$dbName, $dbUser, $dbPass);
    }

    public function getConnection(): PDO
    {
        $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $this->connection;
    }
}