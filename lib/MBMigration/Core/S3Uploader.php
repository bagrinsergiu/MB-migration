<?php

namespace MBMigration\Core;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Exception;

class S3Uploader
{
    private S3Client $s3Client;
    private $bucketName;
    private bool $statusActive;

    public function __construct($statusActive, $awsKey, $awsSecret, $region, $bucketName)
    {
        $this->statusActive = (bool) $statusActive;

        if ($this->statusActive) {
            $this->s3Client = new S3Client([
                'region'      => $region,
                'version'     => 'latest',
                'credentials' => [
                    'key'    => $awsKey,
                    'secret' => $awsSecret,
                ],
            ]);
            $this->bucketName = $bucketName;
        }
    }

    /**
     * @throws Exception
     */
    public function uploadLogFile(int $migrationId, string $filePath): string
    {
        if (!$this->statusActive) {
            throw new Exception("S3Uploader is inactive.");
        }

        if (!file_exists($filePath)) {
            throw new Exception("Log file not found: $filePath");
        }

        $s3Key = 'ministryBrandsMigrationLogs/' . $migrationId;

        try {
            $this->s3Client->putObject([
                'Bucket'      => $this->bucketName,
                'Key'         => $s3Key,
                'Body'        => fopen($filePath, 'r'),
                'ContentType' => 'text/plain',
            ]);

            return $this->s3Client->getObjectUrl($this->bucketName, $s3Key);
        } catch (AwsException $e) {
            throw new Exception("Loading error in S3:" . $e->getMessage());
        }
    }
}
