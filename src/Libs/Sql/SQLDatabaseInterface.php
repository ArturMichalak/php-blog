<?php


namespace Libs\Sql;

use PDO;

interface SQLDatabaseInterface {
    public function getConnection(): PDO;
}