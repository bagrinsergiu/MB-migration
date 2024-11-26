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

    public function __construct($data,$parent=null)
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

        if(is_array($depths[0])) {
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

}
