<?php

namespace MBMigration\Bridge;

use Exception;
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
    private MySQL $db;

    public function __construct(
        Config  $config,
        Request $request
    )
    {
        $this->config = $config;
        $this->request = $request;

        $this->mgResponse = new MgResponse();
        $this->db = $this->doConnectionToDB();
    }

    public function checkPreparedProject(): Bridge
    {
        $this->request->get('source_project_id');

        $this->preparedProject = 1231231231;

        $this->mgResponse
            ->setMessage($this->preparedProject)
            ->setStatusCode(200);

        return $this;
    }

    public function addPreparedProject(): Bridge
    {
        try {
            $inputProperties = $this->checkInputProperties(['brz_project_id', 'source_project_id'], true);
        } catch (Exception $e) {
            $this->prepareResponseMessage($e->getMessage(), 'error', $e->getCode());
            return $this;
        }

        try {
            $this->db->insert('migrations_mapping',
                [
                    'brz_project_id' => (int) $inputProperties['brz_project_id'],
                    'mb_project_uuid' => $inputProperties['source_project_id']
                ]);
        } catch (\Exception $e) {
            $this->prepareResponseMessage($e->getMessage(), 'error', 400);
            return $this;
        }

        $this->prepareResponseMessage(
            [
                'brz_project_id' => (int) $inputProperties['brz_project_id'],
                'source_project_id' => $inputProperties['source_project_id']
            ],
            'message');

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

    private function doConnectionToDB(): MySQL
    {
        $PDOconnection = new MySQL(
            Config::$mgConfigMySQL['dbUser'],
            Config::$mgConfigMySQL['dbPass'],
            Config::$mgConfigMySQL['dbName'],
            Config::$mgConfigMySQL['dbHost'],
        );

        return $PDOconnection->doConnect();
    }

    public function getMessageResponse(): MgResponse
    {
        return $this->mgResponse;
    }

    public function getPreparedMappingList(): Bridge
    {
        $this->allList = [1231231231 => 'asdasd-ewe33-asd-czxcddbn'];

        $this->mgResponse
            ->setMessage($this->allList)
            ->setStatusCode(200);

        return $this;
    }

    public function mappingList()
    {
        switch ($this->request->getMethod()) {
            case 'GET':
                return $this->getPreparedMappingList()
                    ->getMessageResponse();
            case 'POST':
                return $this->addPreparedProject()
                    ->getMessageResponse();
        }
        return $this->getMessageResponse();
    }

    public function prepareResponseMessage($body, $type = 'value', $code = 200): void
    {
        $this->mgResponse
            ->setMessage($body, $type)
            ->setStatusCode($code);
    }

    /**
     * @throws Exception
     */
    private function checkInputProperties($properties, $sendResponse = false): array
    {
        $checkedProperties = null;
        if (is_array($properties)) {
            foreach ($properties as $value) {
                $result = $this->request->get($value);
                if (empty($result)) {
                    throw new Exception("{$value} is empty.", 400);
                }

                $checkedProperties = array_merge([$value => $result], $checkedProperties ?? []);

            }
        } elseif (is_string($properties)) {
            $value = $this->request->get($properties);
            $checkedProperties = [$properties => $value];
        }

        return $checkedProperties;
    }


}
