<?php

namespace MBMigration\Builder;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use JsonSerializable;

/**
 * Section class for dynamically and declaratively building component trees.
 *
 * This class provides a fluent interface for adding components like columns, rows,
 * images, buttons, etc. with support for nesting and property configuration.
 *
 * Example usage:
 *
 * ```php
 * $section = new Section();
 *
 * $section->add()
 *     ->column()
 *     ->setProperties(['width' => 100])
 *     ->add()
 *         ->row()
 *         ->setProperties(['align' => 'center'])
 *         ->add()
 *             ->image()
 *             ->setProperties(['src' => 'img.jpg']);
 * ```
 */
class Section implements JsonSerializable
{
    /**
     * The root component of the section
     *
     * @var BrizyComponent
     */
    protected $rootComponent;

    /**
     * The current component being built
     *
     * @var BrizyComponent
     */
    protected $currentComponent;

    /**
     * Stack of parent components for tracking nesting
     *
     * @var array
     */
    protected $componentStack = [];

    /**
     * Custom component types registry
     *
     * @var array
     */
    protected $componentTypes = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        // Create a root component
        $rootData = [
            'type' => 'Section',
            'value' => [
                'items' => []
            ]
        ];

        $this->rootComponent = new BrizyComponent($rootData);
        $this->currentComponent = $this->rootComponent;

        // Register default component types
        $this->registerComponentType('column', 'Column');
        $this->registerComponentType('row', 'Row');
        $this->registerComponentType('image', 'Image');
        $this->registerComponentType('button', 'Button');
        $this->registerComponentType('wrapper', 'Wrapper');
    }

    /**
     * Register a custom component type
     *
     * @param string $method The method name to use in the fluent interface
     * @param string $type The component type
     * @return $this
     */
    public function registerComponentType(string $method, string $type): Section
    {
        $this->componentTypes[$method] = $type;

        return $this;
    }

    /**
     * Start adding a new component
     *
     * @return $this
     */
    public function add(): Section
    {
        // Push the current component onto the stack
        array_push($this->componentStack, $this->currentComponent);

        return $this;
    }

    /**
     * Create a component of the specified type
     *
     * @param string $type The component type
     * @param array $initialProperties Initial properties for the component
     * @return $this
     */
    public function component(string $type, array $initialProperties = []): Section
    {
        $hasItems = in_array($type, ['Section', 'Column', 'Row', 'SectionItem', 'Line', 'Cloneable', 'Icon', 'RichText', 'Wrapper' ]);;

        $componentData = [
            'type' => $type,
            'value' => $hasItems ? [
                'items' => [],
                '_styles' => [strtolower($type)]
            ] : []
        ];

        // Add initial properties if provided
        foreach ($initialProperties as $key => $value) {
            $componentData['value'][$key] = $value;
        }

        $component = new BrizyComponent($componentData, $this->currentComponent);

        // Add the component to the current component
        $this->currentComponent->getValue()->add_items([$component]);

        // Set the component as the current component
        $this->currentComponent = $component;

        return $this;
    }

    /**
     * Magic method to handle dynamic component creation
     *
     * @param string $name Method name
     * @param array $arguments Method arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        // Check if the method is a registered component type
        if (isset($this->componentTypes[$name])) {
            $initialProperties = $arguments[0] ?? [];
            return $this->component($this->componentTypes[$name], $initialProperties);
        }

        throw new \BadMethodCallException("Method {$name} does not exist");
    }

    /**
     * Add a column component
     *
     * @param array $initialProperties Initial properties for the component
     * @return $this
     */
    public function column(array $initialProperties = [])
    {
        return $this->component('Column', $initialProperties);
    }

    /**
     * Add a row component
     *
     * @param array $initialProperties Initial properties for the component
     * @return $this
     */
    public function row(array $initialProperties = [])
    {
        return $this->component('Row', $initialProperties);
    }

    /**
     * Add an image component
     *
     * @param array $initialProperties Initial properties for the component
     * @return $this
     */
    public function image(array $initialProperties = []): Section
    {
        return $this->component('Image', $initialProperties);
    }

    /**
     * Add a button component
     *
     * @param array $initialProperties Initial properties for the component
     * @return $this
     */
    public function button(array $initialProperties = [])
    {
        return $this->component('Button', $initialProperties);
    }

    /**
     * Set properties for the current component
     *
     * @param array $properties
     * @return $this
     */
    public function setProperties(array $properties)
    {
        foreach ($properties as $key => $value) {
            $this->currentComponent->getValue()->set($key, $value);
        }

        return $this;
    }

    /**
     * Move up to the parent component in the hierarchy
     *
     * @return $this
     */
    public function end()
    {
        if (!empty($this->componentStack)) {
            $this->currentComponent = array_pop($this->componentStack);
        }

        return $this;
    }

    /**
     * Set the depth level for component nesting
     *
     * @param int $level
     * @return $this
     */
    public function depth(int $level)
    {
        // Reset to root
        $this->currentComponent = $this->rootComponent;
        $this->componentStack = [];

        // Navigate to the specified depth
        if ($level > 0) {
            $items = $this->rootComponent->getValue()->get('items');

            for ($i = 0; $i < $level && !empty($items); $i++) {
                $lastIndex = count($items) - 1;
                if ($lastIndex >= 0) {
                    array_push($this->componentStack, $this->currentComponent);
                    $this->currentComponent = $items[$lastIndex];
                    $items = $this->currentComponent->getValue()->get('items');
                }
            }
        }

        return $this;
    }

    /**
     * Get the root component
     *
     * @return BrizyComponent
     */
    public function getRootComponent(): BrizyComponent
    {
        return $this->rootComponent;
    }

    /**
     * Get the current component
     *
     * @return BrizyComponent
     */
    public function getCurrentComponent(): BrizyComponent
    {
        return $this->currentComponent;
    }

    /**
     * Implement JsonSerializable interface
     *
     * @return array
     */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        return $this->rootComponent->jsonSerialize();
    }
}
