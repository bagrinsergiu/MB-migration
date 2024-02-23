<?php

namespace MBMigration\Builder\Utils;

use PHPUnit\Framework\TestCase;

class PathSlugExtractorTest extends TestCase
{
    /**
     * Test to check the functionality of getOrderedPathString method of the PathSlugExtractor class
     * The method should return a forward slash separated string based on the nested associative array it receives
     */
    public function testGetOrderedPathString(): void
    {
        $data = [
            ['slug' => 'home', 'child' => [
                ['slug' => 'media', 'child' => []],
                ['slug' => 'services', 'child' => []],
                ]
            ],
            ['slug' => 'about', 'child' => [
                ['slug' => 'about', 'child' => []],
                ['slug' => 'team', 'child' => []],
                ]
            ],
            ['slug' => 'contact', 'child' => []],
        ];

        // test 'home/services' path
        $result = PathSlugExtractor::getOrderedPathString($data, 'services');
        $this->assertEquals('home/services', $result);

        // test 'about/about' path
        $result = PathSlugExtractor::getOrderedPathString($data, 'about');
        $this->assertEquals('about/about', $result);

        // test 'about/team' path
        $result = PathSlugExtractor::getOrderedPathString($data, 'about');
        $this->assertEquals('about/about', $result);

        // test 'contact' path
        $result = PathSlugExtractor::getOrderedPathString($data, 'contact');
        $this->assertEquals('contact', $result);
        
        // tests for null return when slug not found
        $result = PathSlugExtractor::getOrderedPathString($data, 'not-found');
        $this->assertNull($result);
    }
}