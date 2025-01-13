<?php

namespace MBMigration\Builder\Layout\Common\Objects\Button;

class ButtonStyle
{
    private array $normal = [];
    private array $hover = [];

    public function getNormal(): array
    {
        return $this->normal;
    }

    public function getHover(): array
    {
        return $this->hover;
    }

    public function setNormal(array $normal): ButtonStyle
    {
        $this->normal = $normal;
        return $this;
    }

    public function setHover(array $hover): ButtonStyle
    {
        $this->hover = $hover;
        return $this;
    }

    public function hasData(): bool
    {
        return !empty($this->normal) || !empty($this->hover);
    }
}
