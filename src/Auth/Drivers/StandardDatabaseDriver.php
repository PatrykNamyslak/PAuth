<?php
namespace PatrykNamyslak\Auth\Drivers;

use PatrykNamyslak\Auth\Blueprints\Database as DatabaseBlueprint;
use PatrykNamyslak\Auth\Interfaces\Statement as StatementInterface;


class StandardDatabaseDriver extends DatabaseBlueprint{
    public function query(string $query): StatementInterface{
        
    }
    public function prepare(string $query, array $params): StatementInterface{
        
    }
}