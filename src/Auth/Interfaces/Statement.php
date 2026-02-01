<?php
namespace PatrykNamyslak\Auth\Interfaces;

interface Statement{

    public function fetch(): array|null;
    public function fetchAll(): array|null;
    public function execute(): bool;
}