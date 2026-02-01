<?php
namespace PatrykNamyslak\Auth\Blueprints;

use PatrykNamyslak\Auth\Interfaces\Statement as StatementInterface;
use PatrykNamyslak\Patbase;

abstract class Statement implements StatementInterface{
    private ?Patbase $patbase = null;


    public function __construct(
        protected string $query,
        protected array $params,
        ){}

    abstract public function fetch(): array|null;
    abstract public function fetchAll(): array|null;
    abstract public function execute(): bool;
}