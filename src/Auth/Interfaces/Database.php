<?php
namespace PatrykNamyslak\Auth\Interfaces;

interface Database{
    public function __construct(string $databaseName);
    public function query(string $query): Statement;
    public function prepare(string $query, array $params): Statement;

}