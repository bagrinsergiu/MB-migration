<?php

namespace MBMigration\Builder\BrizyComponent;

/**
 * Facade for BrizyComponent operations
 *
 * This class provides a simplified interface for common operations on BrizyComponent objects.
 * It encapsulates the complex logic of setting properties, adding styles, configuring padding,
 * margin, colors, etc.
 */
class ComponentFacade
{
    /**
     * The component being manipulated
     *
     * @var BrizyComponent
     */
    protected $component;

    /**
     * Constructor
     *
     * @param BrizyComponent $component The component to manipulate
     */
    public function __construct(BrizyComponent $component)
    {
        $this->component = $component;
    }

    /**
     * Get the underlying component
     *
     * @return BrizyComponent
     */
    public function getComponent(): BrizyComponent
    {
        return $this->component;
    }

    /**
     * Set properties on the component
     *
     * @param array $properties The properties to set
     * @return $this
     */
    public function setProperties(array $properties): self
    {
        foreach ($properties as $key => $value) {
            $this->component->getValue()->set($key, $value);
        }

        return $this;
    }

    /**
     * Add styles to the component
     *
     * @param array $styles The styles to add
     * @return $this
     */
    public function addStyles(array $styles): self
    {
        $currentStyles = $this->component->getValue()->get('_styles') ?? [];
        $this->component->getValue()->set('_styles', array_merge($currentStyles, $styles));

        return $this;
    }

    /**
     * Set padding on the component
     *
     * @param int $top Top padding
     * @param int $right Right padding
     * @param int $bottom Bottom padding
     * @param int $left Left padding
     * @param string $suffix The suffix to use (px, %, etc.)
     * @return $this
     */
    public function setPadding(int $top, int $right, int $bottom, int $left, string $suffix = 'px'): self
    {
        $this->component->getValue()->set('paddingType', 'ungrouped');
        $this->component->getValue()->set('paddingTop', $top);
        $this->component->getValue()->set('paddingTopSuffix', $suffix);
        $this->component->getValue()->set('paddingRight', $right);
        $this->component->getValue()->set('paddingRightSuffix', $suffix);
        $this->component->getValue()->set('paddingBottom', $bottom);
        $this->component->getValue()->set('paddingBottomSuffix', $suffix);
        $this->component->getValue()->set('paddingLeft', $left);
        $this->component->getValue()->set('paddingLeftSuffix', $suffix);

        return $this;
    }

    /**
     * Set margin on the component
     *
     * @param int $top Top margin
     * @param int $right Right margin
     * @param int $bottom Bottom margin
     * @param int $left Left margin
     * @param string $suffix The suffix to use (px, %, etc.)
     * @return $this
     */
    public function setMargin(int $top, int $right, int $bottom, int $left, string $suffix = 'px'): self
    {
        $this->component->getValue()->set('marginType', 'ungrouped');
        $this->component->getValue()->set('marginTop', $top);
        $this->component->getValue()->set('marginTopSuffix', $suffix);
        $this->component->getValue()->set('marginRight', $right);
        $this->component->getValue()->set('marginRightSuffix', $suffix);
        $this->component->getValue()->set('marginBottom', $bottom);
        $this->component->getValue()->set('marginBottomSuffix', $suffix);
        $this->component->getValue()->set('marginLeft', $left);
        $this->component->getValue()->set('marginLeftSuffix', $suffix);

        return $this;
    }

    /**
     * Set background color on the component
     *
     * @param string $color The color in hex format
     * @param float $opacity The opacity (0-1)
     * @param string $palette The color palette
     * @return $this
     */
    public function setBackgroundColor(string $color, float $opacity = 1.0, string $palette = ''): self
    {
        $this->component->getValue()->set('bgColorType', 'solid');
        $this->component->getValue()->set('bgColorHex', $color);
        $this->component->getValue()->set('bgColorOpacity', $opacity);
        $this->component->getValue()->set('bgColorPalette', $palette);

        return $this;
    }

    /**
     * Set border radius on the component
     *
     * @param int $radius The border radius
     * @param string $suffix The suffix to use (px, %, etc.)
     * @return $this
     */
    public function setBorderRadius(int $radius, string $suffix = 'px'): self
    {
        $this->component->getValue()->set('borderRadiusType', 'grouped');
        $this->component->getValue()->set('borderRadius', $radius);
        $this->component->getValue()->set('borderRadiusSuffix', $suffix);

        return $this;
    }

    /**
     * Set width and height on the component
     *
     * @param int $width The width
     * @param int $height The height
     * @param string $widthSuffix The width suffix (px, %, etc.)
     * @param string $heightSuffix The height suffix (px, %, etc.)
     * @return $this
     */
    public function setSize(int $width, int $height, string $widthSuffix = 'px', string $heightSuffix = 'px'): self
    {
        $this->component->getValue()->set('width', $width);
        $this->component->getValue()->set('widthSuffix', $widthSuffix);
        $this->component->getValue()->set('height', $height);
        $this->component->getValue()->set('heightSuffix', $heightSuffix);

        return $this;
    }

    /**
     * Set alignment on the component
     *
     * @param string $horizontal The horizontal alignment (left, center, right)
     * @param string $vertical The vertical alignment (top, middle, bottom)
     * @return $this
     */
    public function setAlignment(string $horizontal = 'center', string $vertical = 'middle'): self
    {
        $this->component->getValue()->set('horizontalAlign', $horizontal);
        $this->component->getValue()->set('verticalAlign', $vertical);

        return $this;
    }

    /**
     * Set mobile-specific properties on the component
     *
     * @param array $properties The mobile properties to set
     * @return $this
     */
    public function setMobileProperties(array $properties): self
    {
        foreach ($properties as $key => $value) {
            $this->component->getValue()->set('mobile' . ucfirst($key), $value);
        }

        return $this;
    }

    /**
     * Set tablet-specific properties on the component
     *
     * @param array $properties The tablet properties to set
     * @return $this
     */
    public function setTabletProperties(array $properties): self
    {
        foreach ($properties as $key => $value) {
            $this->component->getValue()->set('tablet' . ucfirst($key), $value);
        }

        return $this;
    }

    /**
     * Add a custom CSS class to the component
     *
     * @param string $className The CSS class name
     * @return $this
     */
    public function addClass(string $className): self
    {
        $currentClasses = $this->component->getValue()->get('customClassName') ?? '';
        $classes = explode(' ', $currentClasses);
        $classes[] = $className;
        $this->component->getValue()->set('customClassName', implode(' ', array_unique(array_filter($classes))));

        return $this;
    }

    /**
     * Add custom CSS to the component
     *
     * @param string $css The CSS to add
     * @return $this
     */
    public function addCustomCSS(string $css): self
    {
        $currentCSS = $this->component->getValue()->get('customCSS') ?? '';
        $this->component->getValue()->set('customCSS', $currentCSS . "\n" . $css);

        return $this;
    }
}
