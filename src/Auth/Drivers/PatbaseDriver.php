<?php
namespace PatrykNamyslak\Auth\Drivers;

use PatrykNamyslak\Auth\Blueprints\Database;
use PatrykNamyslak\Auth\Blueprints\Statement;

class PatbaseDriver extends Database{
    public function __construct(string $databaseName){

    }
    public function query(string $query): Statement{

    }
    public function prepare(string $query, array $params): Statement{
        
    }
}