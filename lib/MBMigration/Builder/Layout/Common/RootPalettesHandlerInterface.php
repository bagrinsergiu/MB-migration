<?php

namespace MBMigration\Builder\Layout\Common;

interface RootPalettesHandlerInterface
{
    public function getSubPalettes(): array;
    public function getSubPaletteByName($name): array;
}
