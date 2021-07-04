<?php

namespace Basduchambre\ExternalDbConnect;

use \PDO;
use Basduchambre\ExternalDbConnect\Exceptions\NoColumns;
use Basduchambre\ExternalDbConnect\Exceptions\WrongDateColumn;
use Basduchambre\ExternalDbConnect\Exceptions\MissingDatabaseSettings;

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
        $this->charset = config('externaldb.external_db.charset');
        $this->columns = config('externaldb.migration.columns');
        $this->date_column = config('externaldb.migration.date_column');
    }

    public function open()
    {
        if (!$this->driver || !$this->host || !$this->port || !$this->database || !$this->charset) {
            throw new MissingDatabaseSettings("Missing settings for the database connections, check your config");
        }
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

    public function query($pdo, $start, $end)
    {
        if (!$this->table) {
            throw new MissingDatabaseSettings("Missing table to retrieve data from, check your config");
        }

        if ($start && $end) {
            // check if date column actually exists inside the columns that will be retrieved
            if (!in_array($this->date_column, array_column($this->columns, 'name'))) {
                throw new WrongDateColumn("The specified time column doesn't exists in your set columns!");
            }
            $query = $pdo->prepare("SELECT * FROM $this->table where $this->date_column > ? and $this->date_column < ?");
            $query->execute([$start, $end]);
        } else {
            $query = $pdo->prepare("SELECT * FROM $this->table");
            $query->execute([]);
        }

        $results = $query->fetchAll();

        return $results;
    }
}
