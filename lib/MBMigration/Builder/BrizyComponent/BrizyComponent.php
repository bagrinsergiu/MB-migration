<?php

namespace MBMigration\Builder\BrizyComponent;

use JsonSerializable;
use Exception;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;

class BrizyComponent implements JsonSerializable
{
    protected $type;
    protected $value;
    protected $blockId;
    protected ?BrizyComponent $parent = null;

    public function __construct($data, ?BrizyComponent $parent = null)
    {
        if (!is_array($data)) {
            throw new BadJsonProvided('Wrong data format provided for BrizyComponent');
        }

        $this->type = $data['type'] ?? '';
        $this->value = new BrizyComponentValue($data['value'] ?? [], $this);
        $this->parent = $parent;

        if (isset($data['blockId'])) {
            $this->blockId = $data['blockId'];
        }
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return BrizyComponent
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): BrizyComponentValue
    {
        return $this->value;
    }

    public function setValue(BrizyComponentValue $value): BrizyComponent
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBlockId()
    {
        return $this->blockId;
    }

    /**
     * @param mixed $blockId
     * @return BrizyComponent
     */
    public function setBlockId($blockId)
    {
        $this->blockId = $blockId;

        return $this;
    }

    public function jsonSerialize()
    {
        $getObjectVars = get_object_vars($this);
        unset($getObjectVars['parent']);

        return $getObjectVars;
    }

    public function getItemWithDepth(): ?BrizyComponent
    {
        $depths = func_get_args();

        if (is_array($depths[0])) {
            $depths = $depths[0];
        }

        $item = null;

        foreach ($depths as $index) {
            if ($item) {
                $items = $item->getValue()->get_items();
            } else {
                $items = $this->getValue()->get_items();
            }

            if (!isset($items[$index])) {
                return $item;
            }

            $item = $items[$index];
        }

        return $item;
    }

    /**
     * @return mixed|null
     */
    public function getParent(): ?BrizyComponent
    {
        return $this->parent;
    }

    public function getItemValueWithDepth(): ?BrizyComponentValue
    {
        $depths = func_get_args();

        return call_user_func_array([$this, 'getItemWithDepth'], $depths)->getValue();
    }


    public function addRadius($radiusPx = 0): BrizyComponent
    {
        $radius = array(
            "borderRadiusType" => "grouped",
            "borderRadius" => $radiusPx,
            "borderRadiusSuffix" => "px",
            "borderTopLeftRadius" => $radiusPx,
            "borderTopLeftRadiusSuffix" => "px",
            "borderTopRightRadius" => $radiusPx,
            "borderTopRightRadiusSuffix" => "px",
            "borderBottomRightRadius" => $radiusPx,
            "borderBottomRightRadiusSuffix" => "px",
            "borderBottomLeftRadius" => $radiusPx,
            "borderBottomLeftRadiusSuffix" => "px",
        );

        foreach ($radius as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addPadding($prefix, $paddingPx = 0): BrizyComponent
    {
        if (is_array($paddingPx)) {
            $padding = [
                "{$prefix}PaddingType" => "ungrouped",
                "{$prefix}Padding" => 0,
                "{$prefix}PaddingSuffix" => "px",
                "{$prefix}PaddingTop" => $paddingPx[0] ?? 0,
                "{$prefix}PaddingTopSuffix" => "px",
                "{$prefix}PaddingRight" => $paddingPx[1] ?? 0,
                "{$prefix}PaddingRightSuffix" => "px",
                "{$prefix}PaddingBottom" => $paddingPx[2] ?? 0,
                "{$prefix}PaddingBottomSuffix" => "px",
                "{$prefix}PaddingLeft" => $paddingPx[3] ?? 0,
                "{$prefix}PaddingLeftSuffix" => "px",
            ];

        } else {
            $padding = [
                "paddingType" => "ungrouped",
                "paddingTop" => $paddingPx,
                "paddingTopSuffix" => "px",
                "paddingBottom" => $paddingPx,
                "paddingBottomSuffix" => "px",
                "paddingRight" => $paddingPx,
                "paddingRightSuffix" => "px",
                "paddingLeft" => $paddingPx,
                "paddingLeftSuffix" => "px",
            ];
        }
        foreach ($padding as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addPadding($prefix, $t, $r, $b, $l): BrizyComponent
    {
        $padding = [
            "{$prefix}PaddingType" => "ungrouped",
            "{$prefix}Padding" => 0,
            "{$prefix}PaddingSuffix" => "px",
            "{$prefix}PaddingTop" => $paddingPx[0] ?? 0,
            "{$prefix}PaddingTopSuffix" => "px",
            "{$prefix}PaddingRight" => $paddingPx[1] ?? 0,
            "{$prefix}PaddingRightSuffix" => "px",
            "{$prefix}PaddingBottom" => $paddingPx[2] ?? 0,
            "{$prefix}PaddingBottomSuffix" => "px",
            "{$prefix}PaddingLeft" => $paddingPx[3] ?? 0,
            "{$prefix}PaddingLeftSuffix" => "px",
        ];
        foreach ($padding as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addGroupedPadding($prefix, $p): BrizyComponent
    {
        $this->addPadding($prefix, $p,$p,$p,$p);

        return $this;
    }



    public function addMobilePadding($paddingPx = 0): BrizyComponent
    {
        if (is_array($paddingPx)) {
            $mobilePadding = [
                "mobilePaddingType" => "ungrouped",
                "mobilePadding" => 0,
                "mobilePaddingSuffix" => "px",
                "mobilePaddingTop" => $paddingPx[0] ?? 0,
                "mobilePaddingTopSuffix" => "px",
                "mobilePaddingRight" => $paddingPx[1] ?? 0,
                "mobilePaddingRightSuffix" => "px",
                "mobilePaddingBottom" => $paddingPx[2] ?? 0,
                "mobilePaddingBottomSuffix" => "px",
                "mobilePaddingLeft" => $paddingPx[3] ?? 0,
                "mobilePaddingLeftSuffix" => "px",
            ];
        } else {
            $mobilePadding = [
                "mobilePaddingType" => "ungrouped",
                "mobilePadding" => $paddingPx,
                "mobilePaddingSuffix" => "px",
                "mobilePaddingTop" => $paddingPx,
                "mobilePaddingTopSuffix" => "px",
                "mobilePaddingRight" => $paddingPx,
                "mobilePaddingRightSuffix" => "px",
                "mobilePaddingBottom" => $paddingPx,
                "mobilePaddingBottomSuffix" => "px",
                "mobilePaddingLeft" => $paddingPx,
                "mobilePaddingLeftSuffix" => "px",
            ];
        }

        foreach ($mobilePadding as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addMobileMargin($MarginPx = 0): BrizyComponent
    {
        if (is_array($MarginPx)) {
            $mobileMargin = [
                "mobileMarginType" => "ungrouped",
                "mobileMargin" => 0,
                "mobileMarginSuffix" => "px",
                "mobileMarginTop" => $MarginPx[0] ?? 0,
                "mobileMarginTopSuffix" => "px",
                "mobileMarginRight" => $MarginPx[1] ?? 0,
                "mobileMarginRightSuffix" => "px",
                "mobileMarginBottom" => $MarginPx[2] ?? 0,
                "mobileMarginBottomSuffix" => "px",
                "mobileMarginLeft" => $MarginPx[3] ?? 0,
                "mobileMarginLeftSuffix" => "px",
            ];

        } else {
            $mobileMargin = [
                "mobileMarginType" => "ungrouped",
                "mobileMargin" => $MarginPx,
                "mobileMarginSuffix" => "px",
                "mobileMarginTop" => $MarginPx,
                "mobileMarginTopSuffix" => "px",
                "mobileMarginRight" => $MarginPx,
                "mobileMarginRightSuffix" => "px",
                "mobileMarginBottom" => $MarginPx,
                "mobileMarginBottomSuffix" => "px",
                "mobileMarginLeft" => $MarginPx,
                "mobileMarginLeftSuffix" => "px",
            ];
        }

        foreach ($mobileMargin as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addPadingLeft($padding = 0, $measureType = 'px'): BrizyComponent
    {
        $padingLeft = [
            "paddingType" => "ungrouped",
            "paddingLeft" => $padding,
            "paddingLeftSuffix" => $measureType,
        ];

        foreach ($padingLeft as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addPadingRight($padding = 0, $measureType = 'px'): BrizyComponent
    {
        $padingLeft = [
            "paddingType" => "ungrouped",
            "paddingRight" => $padding,
            "paddingRightSuffix" => $measureType,
        ];

        foreach ($padingLeft as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addVerticalContentAlign($verticalAlign = 'center'): BrizyComponent
    {
        if (!in_array($verticalAlign, ['center', 'left', 'right'])) {
            $verticalAlign = 'center';
        }

        $this->getValue()->set('verticalAlign', $verticalAlign);
        $this->getValue()->set('mobileVerticalAlign', $verticalAlign);
        $this->getValue()->set('tabletVerticalAlign', $verticalAlign);

        return $this;
    }

    public function addHorizontalContentAlign($verticalAlign = 'center'): BrizyComponent
    {
        if (!in_array($verticalAlign, ['center', 'left', 'right'])) {
            $verticalAlign = 'center';
        }

        $this->getValue()->set('horizontalAlign', $verticalAlign);
        $this->getValue()->set('mobileHorizontalAlign', $verticalAlign);
        $this->getValue()->set('tabletHorizontalAlign', $verticalAlign);

        return $this;
    }

    public function addMobileContentAlign($verticalAlign = 'center'): BrizyComponent
    {
        if (!in_array($verticalAlign, ['center', 'left', 'right'])) {
            $verticalAlign = 'center';
        }

        $this->getValue()->set('mobileHorizontalAlign', $verticalAlign);

        return $this;
    }

    public function addBgColor($hex, $opacity): BrizyComponent
    {
        $bgColor = [
            "bgColorType" => "solid",
            "bgColorHex" => $hex,
            "bgColorOpacity" => $opacity,
            "bgColorPalette" => '',
        ];

        foreach ($bgColor as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }
}
