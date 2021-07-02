<?php

namespace Basduchambre\ExternalDbConnect;

use \PDO;
use DateTime;
use Basduchambre\ExternalDbConnect\Connection;
use Basduchambre\ExternalDbConnect\Exceptions\NoColumns;

class ExternalDbConnect
{
    private $start;
    private $end;

    public function __construct()
    {
        $this->columns = config('externaldb.migration.columns');
        $this->end = date("Y-m-d", strtotime("now")); // default
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
        // check if there are any columns configured
        if (!count($this->columns) > 0) {
            throw new NoColumns("There are no columns configured!");
        }

        // open connection
        $connection = new Connection();
        $pdo = $connection->open();

        // perform query and retrieve results
        $results = $connection->query($pdo, $this->start, $this->end);

        // close connection
        $pdo = $connection->close($pdo);

        // filter results by wanted columns
        $columns = array_column($this->columns, 'name');

        foreach ($results as &$result) {
            $result = array_intersect_key($result, array_flip($columns));
        }

        return $results;
    }
}
