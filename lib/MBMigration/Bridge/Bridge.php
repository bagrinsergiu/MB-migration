<?php

namespace MBMigration\Bridge;

use MBMigration\Core\Config;
use MBMigration\Layer\DataSource\driver\MySQL;
use Symfony\Component\HttpFoundation\Request;

class Bridge
{
    private Config $config;

    private MgResponse $mgResponse;
    private string $sourceProject;
    private Request $request;
    private array $allList;
    private int $preparedProject;

    public function __construct(
        Config $config,
        Request $request
    )
    {
        $this->config = $config;
        $this->request = $request;

        $this->mgResponse = new MgResponse();
    }

    public function checkPreparedProject(): Bridge
    {
        $this->doConnectionToDB();

        $this->request->get('source_project_id');

        $this->preparedProject = 1231231231;

        $this->mgResponse
            ->setMessage($this->preparedProject)
            ->setStatusCode(200);

        return $this;
    }

    public function getSourceProject()
    {
        return $this->sourceProject;
    }

    public function setSourceProject($sourceProject)
    {
        $this->sourceProject = $sourceProject;

        return $this;
    }

    private function doConnectionToDB()
    {
        $PDOconnection = new MySQL(
            Config::$mgConfigMySQL['dbUser'],
            Config::$mgConfigMySQL['dbPass'],
            Config::$mgConfigMySQL['dbName'],
            Config::$mgConfigMySQL['dbHost'],
        );
        $PDOconnection->doConnect();

    }

    public function getMessageResponse(): MgResponse
    {
        return $this->mgResponse;
    }

    public function getPreparedMappingList(): Bridge
    {
        $this->allList = [1231231231=> 'asdasd-ewe33-asd-czxcddbn'];

        $this->mgResponse
            ->setMessage($this->allList)
            ->setStatusCode(200);

        return $this;
    }


}
