<?php

namespace MBMigration\Tests\Builder\Media;

use MBMigration\Builder\Media\MediaController;
use MBMigration\Builder\Utils\ArrayManipulator;
use MBMigration\Core\Config;
use MBMigration\Core\Utils;
use MBMigration\Layer\Brizy\BrizyAPI;
use PHPUnit\Framework\TestCase;

/**
 * @coversDefaultClass \MBMigration\Builder\Media\MediaController
 */
class MediaControllerTest extends TestCase
{
    /**
     * @var MediaController
     */
    protected $mediaController;

    protected function setUp(): void
    {
        $this->mediaController = new MediaController();
    }

    /**
     * @covers ::getPicturesUrl
     */
    public function testGetPicturesUrl(): void
    {
        $uuid = 'uuid-test-value';
        $type = 'gallery-layout';
        $nameImage = 'testImage.jpg';

        $url = $this->invokeMethod($this->mediaController, 'getPicturesUrl', [$nameImage, $type, $uuid]);

        $folderLoad = '/gallery/slides/'; // As per the getPicturesUrl function
        $prefix = substr($uuid, 0, 2);
        $expectedUrl = Config::$MBMediaStaging."/".$prefix.'/'.$uuid.$folderLoad.$nameImage;

        $this->assertEquals($expectedUrl, $url);
    }

    /**
     * Use this method to call protected/private methods
     *
     * @throws \ReflectionException
     */
    protected function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}