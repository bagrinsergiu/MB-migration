<?php

namespace Brizy\Layer\DataSource\driver;

use Brizy\core\Config;
use PDO;
use phpseclib\Crypt\RSA;
use phpseclib\Net\SSH2;


class PostgreSQL
{
    private SSH2 $ssh;
    private PDO $pdo;

    public function __construct()
    {
        $config = Config::configPostgreSQL();

        $dbHost = $config['dbHost'];
        $dbPort = $config['dbPort'];
        $dbName = $config['dbName'];

        $this->ssh = new SSH2($config['sshHost']);
        $key = new RSA();
        $key->loadKey(file_get_contents($config['sshPrivateKeyPath']));

        if (!$this->ssh->login($config['sshUser'], $key)) {
            die('SSH Login Failed');
        }

        $this->pdo = new PDO(
            "pgsql:host=$dbHost;port=$dbPort;dbname=$dbName",
            $config['dbUser'],
            $config['dbPassword']
        );
    }

    public function request($query): bool|\PDOStatement
    {
        return $this->pdo->query($query);
    }
}