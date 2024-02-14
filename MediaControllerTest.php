<?php

namespace MBMigration\Tests;

use MBMigration\Builder\Media\MediaController;
use MBMigration\Layer\Brizy\BrizyAPI;
use PHPUnit\Framework\TestCase;

class MediaControllerTest extends TestCase
{
    /**
     * @var MediaController
     */
    private $mediaController;

    /**
     * @var BrizyAPI
     */
    private $brizyApiMock;

    protected function setUp(): void
    {
        $this->brizyApiMock = $this->createMock(BrizyAPI::class);

        // Instantiate MediaController class with the mock BrizyAPI object
        $this->mediaController = new MediaController($this->brizyApiMock);
    }

    /**
     * Tests media method of the MediaController class
     */
    public function testMedia(): void
    {
        // Define input item
        $item = [
            'category' => 'photo',
            'content' => 'test_content',
            'uploadStatus' => false,
            'imageFileName' => '',
        ];
        $section = 'Some_section';
        $projectId = 'project_1';
        $uuid = 'uuid_1';
        $expectedItem = [
            'category' => $item['category'],
            'content' => 'test_name',
            'uploadStatus' => true,
            'imageFileName' => 'test_filename',
        ]; 

        $result = [
            'status' => 201,
            'body' => json_encode(
                [
                    'filename' => $expectedItem['imageFileName'],
                    'name' => $expectedItem['content']
                ]
            )
        ];

        // Setup the expectation for the brizyApiMock
        $this->brizyApiMock
            ->method('createMedia')
            ->with('UrlFor_test_content', 'project_1')
            ->will($this->returnValue($result));

        $mediaRef = $this->getMethod('media');
        // Run the test for the media method
        $mediaRef->invokeArgs(
            $this->mediaController,
            [&$item, $section, $this->brizyApiMock, $projectId, $uuid]
        );

        // Check the result is as expected
        $this->assertEquals($expectedItem, $item);
    }

    // Helper function to get protected and private methods
    protected function getMethod($name): \ReflectionMethod
    {
        $class = new \ReflectionClass(MediaController::class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method;
    }
}