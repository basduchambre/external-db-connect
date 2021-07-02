<?php

namespace Basduchambre\ExternalDbConnect;

use \PDO;
use DateTime;
use Basduchambre\ExternalDbConnect\Connection;
use Basduchambre\ExternalDbConnect\Exceptions\NoColumns;
use Basduchambre\ExternalDbConnect\Exceptions\NoTimeColumn;

class ExternalDbConnect
{
    private $start;
    private $end;
    private $timecolumn;

    public function __construct()
    {
        $this->columns = config('externaldb.migration.columns');
        $this->start = date("Y-m-d", strtotime("-7 days")); // default
        $this->end = date("Y-m-d", strtotime("today")); // default
    }

    public function timecolumn(string $timecolumn)
    {
        $this->timecolumn = $timecolumn;

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
        // return $this->end;
        // open connection
        $connection = new Connection();
        $pdo = $connection->open();

        // perform query
        if ($this->start && $this->end && !$this->timecolumn) {
            throw new NoTimeColumn("The time column isn't specified, can't filter on time");
        }
        $results = $connection->query($pdo, $this->timecolumn, $this->start, $this->end);

        // close connection
        $pdo = $connection->close($pdo);

        // filter results by wanted columns
        if (!count($this->columns) > 0) {
            throw new NoColumns("There are no columns configured!");
        }

        $columns = array_column($this->columns, 'name');

        foreach ($results as &$result) {
            $result = array_intersect_key($result, array_flip($columns));
        }

        return $results;
    }
}
