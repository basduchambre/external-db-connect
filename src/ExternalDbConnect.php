<?php

namespace Basduchambre\ExternalDbConnect;

use Basduchambre\ExternalDbConnect\Connection;
use Basduchambre\ExternalDbConnect\Exceptions\NoColumns;

class ExternalDbConnect
{

    public function __construct()
    {
        $this->columns = config('externaldb.migration.columns');
        $this->tables = config('externaldb.external_db.table');
    }

    public function timetable(string $start)
    {
        $this->start = $start;

        return $this;
    }

    public function start(string $start)
    {
        $this->start = $start;

        return $this;
    }

    public function end(string $end)
    {
        $this->end = $end;

        return $this;
    }

    public function get()
    {
        $connection = new Connection();
        $pdo = $connection->open();

        if (!count($this->columns) > 0) {
            throw new NoColumns("There are no columns configured!");
        }

        $columns = implode(', ', array_column($this->columns, 'name'));

        $query = $pdo->prepare("SELECT $columns FROM $this->tables");
        $query->execute([]);

        $results = $query->fetchAll();

        $pdo = $connection->close($pdo);

        return $results;
    }
}
