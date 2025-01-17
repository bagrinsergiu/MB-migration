<?php

namespace MBMigration\Builder\Layout\Common\DTOObjects\Head;

class HeadStyle
{
    private int $height = 10;

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
