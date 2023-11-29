<?php

namespace MBMigration\Builder\BrizyComponent;

class BrizyComponent implements \JsonSerializable
{
    protected $type;
    protected $value;
    protected $blockId;

    public function __construct($data)
    {
        if (!is_array($data)) {
            throw new \Exception('Wrong data format provided for BrizyComponent');
        }

        $this->type = $data['type'] ?? '';
        $this->value = new BrizyComponentValue($data['value'] ?? []);

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

        return $getObjectVars;
    }

    public function getItemWithDepth()
    {
        $depths = func_get_args();
        $item = null;
        foreach ($depths as $index) {
            if ($item) {
                $item = $item->getValue()->get_items()[$index];
            } else {
                $item = $this->getValue()->get_items()[$index];
            }
        }

        return $item;
    }

    public function getItemValueWithDepth()
    {
        $depths = func_get_args();

        return call_user_func_array([$this, 'getItemWithDepth'], $depths)->getValue();
    }

}