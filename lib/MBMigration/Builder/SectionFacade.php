<?php

namespace MBMigration\Builder;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\ComponentFacade;

/**
 * Facade for Section operations
 *
 * This class provides a simplified interface for working with the Section class.
 * It encapsulates the complex logic of creating and manipulating sections.
 */
class SectionFacade
{
    /**
     * The section being manipulated
     *
     * @var Section
     */
    protected $section;

    /**
     * Constructor
     *
     * @param Section|null $section The section to manipulate, or null to create a new one
     */
    public function __construct(Section $section = null)
    {
        $this->section = $section ?? new Section();
    }

    /**
     * Get the underlying section
     *
     * @return Section
     */
    public function getSection(): Section
    {
        return $this->section;
    }

    /**
     * Add a component to the section
     *
     * @param string $type The component type
     * @param array $properties The component properties
     * @return ComponentFacade A facade for the added component
     */
    public function addComponent(string $type, array $properties = []): ComponentFacade
    {
        $this->section->add()->component($type, $properties);
        return new ComponentFacade($this->section->getCurrentComponent());
    }

    /**
     * Add a column to the section
     *
     * @param array $properties The column properties
     * @return ComponentFacade A facade for the added column
     */
    public function addColumn(array $properties = []): ComponentFacade
    {
        $this->section->add()->column($properties);
        return new ComponentFacade($this->section->getCurrentComponent());
    }

    /**
     * Add a row to the section
     *
     * @param array $properties The row properties
     * @return ComponentFacade A facade for the added row
     */
    public function addRow(array $properties = []): ComponentFacade
    {
        $this->section->add()->row($properties);
        return new ComponentFacade($this->section->getCurrentComponent());
    }

    /**
     * Add an image to the section
     *
     * @param array $properties The image properties
     * @return ComponentFacade A facade for the added image
     */
    public function addImage(array $properties = []): ComponentFacade
    {
        $this->section->add()->image($properties);


        return new ComponentFacade($this->section->getCurrentComponent());
    }

    /**
     * Add a button to the section
     *
     * @param array $properties The button properties
     * @return ComponentFacade A facade for the added button
     */
    public function addButton(array $properties = []): ComponentFacade
    {
        $this->section->add()->button($properties);
        return new ComponentFacade($this->section->getCurrentComponent());
    }

    /**
     * Add a wrapper to the section
     *
     * @param array $properties The wrapper properties
     * @return ComponentFacade A facade for the added wrapper
     */
    public function addWrapper(array $properties = []): ComponentFacade
    {
        $this->section->add()->component('Wrapper', $properties);
        return new ComponentFacade($this->section->getCurrentComponent());
    }

    /**
     * Add a rich text component to the section
     *
     * @param array $properties The rich text properties
     * @return ComponentFacade A facade for the added rich text
     */
    public function addRichText(array $properties = []): ComponentFacade
    {
        $this->section->add()->component('RichText', $properties);
        return new ComponentFacade($this->section->getCurrentComponent());
    }

    /**
     * Add a section item to the section
     *
     * @param array $properties The section item properties
     * @return ComponentFacade A facade for the added section item
     */
    public function addSectionItem(array $properties = []): ComponentFacade
    {
        $this->section->add()->component('SectionItem', $properties);
        return new ComponentFacade($this->section->getCurrentComponent());
    }

    /**
     * Add a line to the section
     *
     * @param array $properties The line properties
     * @return ComponentFacade A facade for the added line
     */
    public function addLine(array $properties = []): ComponentFacade
    {
        $this->section->add()->component('Line', $properties);
        return new ComponentFacade($this->section->getCurrentComponent());
    }

    /**
     * Add a cloneable to the section
     *
     * @param array $properties The cloneable properties
     * @return ComponentFacade A facade for the added cloneable
     */
    public function addCloneable(array $properties = []): ComponentFacade
    {
        $this->section->add()->component('Cloneable', $properties);
        return new ComponentFacade($this->section->getCurrentComponent());
    }

    /**
     * Add an icon to the section
     *
     * @param array $properties The icon properties
     * @return ComponentFacade A facade for the added icon
     */
    public function addIcon(array $properties = []): ComponentFacade
    {
        $this->section->add()->component('Icon', $properties);
        return new ComponentFacade($this->section->getCurrentComponent());
    }

    /**
     * Register a custom component type
     *
     * @param string $method The method name to use in the fluent interface
     * @param string $type The component type
     * @return $this
     */
    public function registerComponentType(string $method, string $type): self
    {
        $this->section->registerComponentType($method, $type);
        return $this;
    }

    /**
     * Move up to the parent component in the hierarchy
     *
     * @return $this
     */
    public function end(): self
    {
        $this->section->end();
        return $this;
    }

    /**
     * Set the depth level for component nesting
     *
     * @param int $level The depth level
     * @return $this
     */
    public function depth(int $level): self
    {
        $this->section->depth($level);
        return $this;
    }

    /**
     * Get a facade for the current component
     *
     * @return ComponentFacade
     */
    public function getCurrentComponent(): ComponentFacade
    {
        return new ComponentFacade($this->section->getCurrentComponent());
    }

    /**
     * Get a facade for the root component
     *
     * @return ComponentFacade
     */
    public function getRootComponent(): ComponentFacade
    {
        return new ComponentFacade($this->section->getRootComponent());
    }

    /**
     * Convert the section to JSON
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->section);
    }

    /**
     * Get the section as an array
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->section->jsonSerialize();
    }
}
