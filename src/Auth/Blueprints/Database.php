<?php
namespace PatrykNamyslak\Auth\Blueprints;

use PatrykNamyslak\Auth\Enums\DatabaseDriverType;
use PatrykNamyslak\Auth\Interfaces\Database as DatabaseInterface;
use PatrykNamyslak\Patbase;

abstract class Database implements DatabaseInterface
{
    private string $dsn;
    protected(set) Patbase $db;

    public function __construct(
        protected DatabaseDriverType $driverType, 
        protected string $host, 
        protected string $username, 
        protected string $password, 
        protected string $database,
        protected int|string|null $port = NULL,
    ){
        $this->port = match($this->port){
            null => $this->getDefaultPort(),
            default => $this->port,
        };
        $this->dsn = $this->generateDsn();
        $this->db = new Patbase(database: $database, username: $username, password: $password, host: $host, autoConnect: false);
        $this->db->dsn = $this->dsn;
        $this->db->connect();
    }

    protected function getDefaultPort(){
        return match($this->driverType){
            DatabaseDriverType::SQL_LITE => NULL,
            DatabaseDriverType::MYSQL => 3306,
            DatabaseDriverType::POSTGRES => 5432,
            DatabaseDriverType::MS_SQL_SERVER, DatabaseDriverType::MS_SQL_SERVER_LINUX => 1433,
            DatabaseDriverType::ORACLE => 1521,
            DatabaseDriverType::FIREBIRD => 3050,
            DatabaseDriverType::IBM_DB2, DatabaseDriverType::OPEN_DATABASE_CONNECTIVITY => 50000,
        };
    }

    /**
     * Generates a DSN for pretty much any PDO-compatible driver.
     */
    protected function generateDsn(): string{
        return match ($this->driverType){
            // MySQL / MariaDB
            DatabaseDriverType::MYSQL => "mysql:host={$this->host};port={$this->port};dbname={$this->database};charset=utf8mb4",
            DatabaseDriverType::POSTGRES => "pgsql:host={$this->host};port={$this->port};dbname={$this->database}",
            DatabaseDriverType::SQL_LITE => "sqlite:{$this->database}",
            DatabaseDriverType::MS_SQL_SERVER => "sqlsrv:Server={$this->host},{$this->port};Database={$this->database}",
            DatabaseDriverType::MS_SQL_SERVER_LINUX => "dblib:host={$this->host}:{$this->port};dbname={$this->database}",
            DatabaseDriverType::ORACLE => "oci:dbname=//{$this->host}:{$this->port}/{$this->database}",
            DatabaseDriverType::FIREBIRD => "firebird:dbname={$this->host}/{$this->port}:{$this->database}",
            DatabaseDriverType::IBM_DB2, DatabaseDriverType::OPEN_DATABASE_CONNECTIVITY => "odbc:DRIVER={IBM DB2 ODBC DRIVER};DATABASE={$this->database};HOSTNAME={$this->host};PORT={$this->port};PROTOCOL=TCPIP;",
            default => "mysql:host={$this->host};dbname={$this->database}"
        };
    }
}