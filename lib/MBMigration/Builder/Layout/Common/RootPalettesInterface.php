<?php

namespace MBMigration\Builder\Layout\Common;

interface RootPalettesInterface
{
    public function getSubPalettes(): array;
    public function getSubPaletteByName($name): array;
}
