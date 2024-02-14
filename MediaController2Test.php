<?php

namespace MBMigration\Builder\Media;

use PHPUnit\Framework\TestCase;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Builder\VariableCache;

class MediaController2Test extends TestCase
{
    /**
     * Testing MediaController:uploadPicturesFromSections method.
     *
     * The key goal of this test is to verify the uploadPicturesFromSections
     * method of MediaController. We'll be verifying the expected output
     * array content after background photos have been uploaded.
     */
    public function testUploadPicturesFromSections()
    {
        // Mocking Utils to only return information logged for debugging
        $utilsMock = $this->getMockBuilder('MBMigration\Core\Utils')
            ->getMock();
        $utilsMock->expects($this->any())
            ->method('log')
            ->willReturn(null);

        // Mocking ArrayManipulator checkArrayPath method to always return true
        $arrayManipulatorMock = $this->getMockBuilder('MBMigration\Builder\Utils\ArrayManipulator')
            ->getMock();
        $arrayManipulatorMock->expects($this->any())
            ->method('checkArrayPath')
            ->willReturn(true);

        // Mocking BrizyAPI to simulate media being uploaded successfully
        $brizyApiMock = $this->getMockBuilder('MBMigration\Layer\Brizy\BrizyAPI')
            ->getMock();
        $brizyApiMock->expects($this->any())
            ->method('createMedia')
            ->willReturn([
                'body' => json_encode([
                    'name' => 'newname',
                    'filename' => 'newfilename'
                ])
            ]);

        // Preparing test input
        $sectionsItems = [
            [
                'settings' => [
                    'sections' => [
                        'background' => [
                            'photo' => 'photo1'
                        ]
                    ],
                    'background' => [
                        'photo' => 'photo2'
                    ]
                ],
                'items' => [
                    [
                        'category' => 'photo',
                       'content' => 'photo2'
                    ]
                ],
                'typeSection' => 'type1'
            ]
        ];

        $projectId = 1;
        $uuid = 1;

        // Actual testing
        $controller = new MediaController();
        $result = $controller->uploadPicturesFromSections($sectionsItems, $brizyApiMock, $projectId, $uuid);

        // Expects the photo properties to be replaced with new name and file name after being uploaded
        $this->assertEquals($result[0]['settings']['sections']['background']['photo'], 'newname');
        $this->assertEquals($result[0]['settings']['sections']['background']['filename'], 'newfilename');
        $this->assertEquals($result[0]['settings']['background']['photo'], 'newname');
        $this->assertEquals($result[0]['settings']['background']['filename'], 'newfilename');
    }
}