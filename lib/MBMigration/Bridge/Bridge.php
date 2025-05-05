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
    private array $allList = [];
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
        try {
            $inputProperties = $this->checkInputProperties(['source_project_id'], true);

            switch ($this->request->getMethod()) {
                case 'GET':
                    $this->preparedSearchByUUID($inputProperties['source_project_id']);
                    break;
            }

        } catch (\Exception $e) {
            $this->prepareResponseMessage($e->getMessage(), 'error', 400);
        }

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

        $this->insertMigrationMapping($inputProperties['brz_project_id'], $inputProperties['source_project_id']);

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
        try {
            $allList = $this->db->getAllRows('SELECT * FROM migrations_mapping');

            foreach ($allList as $value) {
                $this->allList[(int) $value['brz_project_id']] = $value['mb_project_uuid'];
            }

            $this->mgResponse
                ->setMessage($this->allList)
                ->setStatusCode(200);

        } catch (\Exception $e) {
            $this->mgResponse
                ->setMessage($e->getMessage(), 'error')
                ->setStatusCode(200);
        }

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

    public function insertMigrationMapping($brz_project_id, $source_project_id, $mata_data = '{}')
    {
        try {
            $this->db->insert('migrations_mapping',
                [
                    'brz_project_id' => (int) $brz_project_id,
                    'mb_project_uuid' => $source_project_id,
                    'changes_json' => $mata_data
                ]);
        } catch (\Exception $e) {
            $this->prepareResponseMessage($e->getMessage(), 'error', 400);
        }
    }

    /**
     * @throws Exception
     */
    public function checkPageChanges($mbProjectId, array $pageList): bool
    {
        try {
            $mgr_resultBrzId = $this->searchByUUID($mbProjectId);







        } catch (\Exception $e) {
            $this->mgResponse
                ->setMessage($e->getMessage(), 'error')
                ->setStatusCode(400);
        }

        return true;
    }

    /**
     * @throws Exception
     */
    private function searchByUUID(string $inputProperties): int
    {
        try {
            $brzID = $this->db->find('SELECT brz_project_id FROM migrations_mapping WHERE mb_project_uuid = ?', [$inputProperties]);

            if (empty($brzID['brz_project_id'])) {
                throw new Exception('Project not found', 400);
            }

            return (int) $brzID['brz_project_id'];
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
    }

    private function preparedSearchByUUID($source_project_id)
    {
        try {
            $resultBrzId = $this->searchByUUID($source_project_id);

            $this->mgResponse
                ->setMessage($resultBrzId)
                ->setStatusCode(200);
        } catch (\Exception $e) {
            $this->mgResponse
                ->setMessage($e->getMessage(), 'error')
                ->setStatusCode(400);
        }
    }

    public function checkPageChangesReport(): string
    {

        return '';
    }
}
