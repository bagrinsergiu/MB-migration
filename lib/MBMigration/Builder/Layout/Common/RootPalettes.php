<?php

namespace MBMigration\Builder\Layout\Common;

class RootPalettes implements RootPalettesInterface
{
    private array $rootPalettes;

    public function __construct(array $rootPalettes)
    {
        $this->rootPalettes = $rootPalettes;
        return $this;
    }

    public function getSubPalettes(): array
    {
        return $this->rootPalettes;
    }

    public function getSubPaletteByName($name): array
    {
        if (array_key_exists($name, $this->rootPalettes)) {
            return $this->rootPalettes[$name];
        }
        return $this->rootPalettes['subpalette1'] ?? [];
    }
}
