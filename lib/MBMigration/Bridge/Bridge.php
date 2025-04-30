<?php

namespace MBMigration\Bridge;

use MBMigration\Core\Config;

class Bridge
{
    private Config $config;
    private string $sourceProject;

    public function __construct(
        Config $config
    )
    {
        $this->config = $config;

        return $this;
    }

    public function checkPreparedProject()
    {

        $this->doConnectionToLocalDB();
        $preparedProjectID = null;


        return $preparedProjectID;

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

    private function doConnectionToLocalDB()
    {




    }


}
