<?php
namespace PatrykNamyslak\Auth\Blueprints;

use PatrykNamyslak\Patbase;

abstract class Repository{
    abstract protected (Config::$databaseClass) $db; // Be able to fetch the class like in the config it would be MyNameSpace\Database::class
    abstract protected string $table;
}