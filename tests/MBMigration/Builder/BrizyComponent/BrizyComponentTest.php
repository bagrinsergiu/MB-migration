<?php

declare(strict_types=1);

namespace Tests\MBMigration\Builder\BrizyComponent;

use MBMigration\Builder\BrizyComponent\BrizyColumComponent;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyLineComponent;
use MBMigration\Builder\BrizyComponent\BrizyRowComponent;
use MBMigration\Builder\BrizyComponent\BrizyWrapperComponent;
use PHPUnit\Framework\TestCase;

class BrizyComponentTest extends TestCase
{
    public function testFactoryReturnsSubclass(): void
    {
        $row = BrizyComponent::fromArray([
            'type' => 'Row',
            'value' => [
                '_styles' => ['row'],
                'items' => []
            ]
        ]);
        $this->assertInstanceOf(BrizyRowComponent::class, $row);

        $column = BrizyComponent::fromArray([
            'type' => 'Column',
            'value' => [
                '_styles' => ['column'],
                'items' => []
            ]
        ]);
        $this->assertInstanceOf(BrizyColumComponent::class, $column);

        $line = BrizyComponent::fromArray([
            'type' => 'Line',
            'value' => [
                '_styles' => ['line']
            ]
        ]);
        $this->assertInstanceOf(BrizyLineComponent::class, $line);
    }

    public function testFactoryFallbackToBaseComponent(): void
    {
        $unknown = BrizyComponent::fromArray([
            'type' => 'UnknownType',
            'value' => []
        ]);
        $this->assertInstanceOf(BrizyComponent::class, $unknown);
        $this->assertNotInstanceOf(BrizyRowComponent::class, $unknown);
        $this->assertNotInstanceOf(BrizyColumComponent::class, $unknown);
        $this->assertNotInstanceOf(BrizyLineComponent::class, $unknown);
    }

    public function testDefaultConstructorsStructure(): void
    {
        $row = new BrizyRowComponent();
        $this->assertSame('Row', $row->getType());
        $this->assertIsArray($row->getValue()->get('_styles'));
        $this->assertContains('row', $row->getValue()->get('_styles'));
        $this->assertIsArray($row->getValue()->get('items'));

        $column = new BrizyColumComponent();
        $this->assertSame('Column', $column->getType());
        $this->assertIsArray($column->getValue()->get('_styles'));
        $this->assertContains('column', $column->getValue()->get('_styles'));
        $this->assertIsArray($column->getValue()->get('items'));

        $line = new BrizyLineComponent();
        $this->assertSame('Line', $line->getType());
        $this->assertIsArray($line->getValue()->get('_styles'));
        $this->assertContains('line', $line->getValue()->get('_styles'));
    }

    public function testNestedItemsBuiltViaFactoryAndParentLinked(): void
    {
        $parent = new BrizyComponent([
            'type' => 'Section',
            'value' => [
                'items' => [
                    [
                        'type' => 'Row',
                        'value' => [
                            '_styles' => ['row'],
                            'items' => []
                        ]
                    ]
                ]
            ]
        ]);

        $items = $parent->getValue()->get('items');
        $this->assertIsArray($items);
        $this->assertCount(1, $items);
        $child = $items[0];
        $this->assertInstanceOf(BrizyRowComponent::class, $child);
        $this->assertSame($parent, $child->getParent());
    }

    public function testWrapperComponentStructureAndParent(): void
    {
        $parent = new BrizyComponent(['type' => 'Section', 'value' => ['items' => []]]);
        $wrapper = new BrizyWrapperComponent('wrapper-clone', $parent);
        $this->assertSame('Wrapper', $wrapper->getType());
        $styles = $wrapper->getValue()->get('_styles');
        $this->assertIsArray($styles);
        $this->assertContains('wrapper', $styles);
        $this->assertContains('wrapper-clone', $styles);
        $this->assertSame($parent, $wrapper->getParent());
    }
}
