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
    protected $parent;

    public function __construct($data, $parent = null)
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

    public function getItemWithDepth()
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
    public function getParent()
    {
        return $this->parent;
    }

    public function getItemValueWithDepth()
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

    public function addPadding($paddingPx = 0): BrizyComponent
    {
        $radius = [
            "paddingType" => "ungrouped",
            "paddingTop" => $paddingPx,
            "paddingTopSuffix" => "px",
            "paddingBottom" => $paddingPx,
            "paddingBottomSuffix" => "px",
            "paddingRight" => $paddingPx,
            "paddingRightSuffix" => "px",
            "paddingLeft" => $paddingPx,
            "paddingLeftSuffix" => "px",];

        foreach ($radius as $key => $value) {
            $this->getValue()->set($key, $value);
        }
        return $this;
    }

    public function addMobilePadding($paddingPx = 0): BrizyComponent
    {
        if(is_array($paddingPx)){
            $mobilePadding = [
                "mobilePaddingType"=> "ungrouped",
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
                "mobilePaddingType"=> "ungrouped",
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
        if(is_array($MarginPx)){
            $mobileMargin = [
                "mobileMarginType"=> "ungrouped",
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
                "mobileMarginType"=> "ungrouped",
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
}
