<?php

namespace MBMigration\Layer\DataSource\driver;

use Exception;
use PDO;
use PDOException;

class MySQL
{
    private $pdo;
    private string $dsn;
    /**
     * @var mixed
     */
    private $password;
    /**
     * @var mixed
     */
    private $userName;

    public function __construct($username, $password, $dbname = 'migration_DB', $host = 'localhost' ) {
        $this->setDSN($host, $dbname);
        $this->setUserName($username);
        $this->setPassword($password);
    }

    /**
     * @throws Exception
     */
    public function doConnect(): MySQL
    {
        try {
            $this->pdo = new PDO(
                $this->getDSN(),
                $this->getUserName(),
                $this->getPassword()
            );

            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: ".$e->getMessage());
        }

        return $this;
    }

    public function getSingleValue($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function getAllRows($sql, $params = []) {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function insert($table, $data) {
        $columns = implode(", ", array_keys($data));
        $placeholders = ":" . implode(", :", array_keys($data));

        $sql = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $stmt = $this->pdo->prepare($sql);

        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }

        $stmt->execute();
        return $this->pdo->lastInsertId();
    }

    private function setUserName($username)
    {
        $this->userName = $username;
    }

    private function setDSN($host, $dbname, $charset = 'utf8mb4')
    {
        $this->dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";
    }

    private function getDSN(): string
    {
        return $this->dsn;
    }

    private function setPassword($password)
    {
        $this->password = $password;
    }

    private function getUserName()
    {
        return $this->userName;
    }

    private function getPassword()
    {
        return $this->password;
    }

}
