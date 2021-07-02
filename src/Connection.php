<?php

namespace Basduchambre\ExternalDbConnect;

use \PDO;

class Connection
{
    public function __construct()
    {
        $this->driver = config('externaldb.external_db.driver');
        $this->host = config('externaldb.external_db.host');
        $this->port = config('externaldb.external_db.port');
        $this->database = config('externaldb.external_db.database');
        $this->table = config('externaldb.external_db.table');
        $this->username = config('externaldb.external_db.username');
        $this->password = config('externaldb.external_db.password');
        //$this->unix_socket = config('externaldb.external_db.unix_socket');
        $this->charset = config('externaldb.external_db.charset');
        $this->columns = config('externaldb.migration.columns');
    }

    public function open()
    {
        $dsn = "$this->driver:host=$this->host;port=$this->port;dbname=$this->database;charset=$this->charset";

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $pdo = new PDO($dsn, $this->username, $this->password, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }

        return $pdo;
    }

    public function close($pdo)
    {
        $pdo = null;

        return $pdo;
    }
}
