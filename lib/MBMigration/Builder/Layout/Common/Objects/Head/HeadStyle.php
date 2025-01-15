<?php

namespace MBMigration\Builder\Layout\Common\Objects\Head;

class HeadStyle
{
    private int $height;

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): HeadStyle
    {
        $this->height = $height;
        return $this;
    }
}
