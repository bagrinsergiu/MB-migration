<?php

namespace MBMigration\Builder\Layout\Theme\Dusk\SectionHandlers;

interface SectionHandlerInterface
{
    public function supports(array $section): bool;
    public function handle(array &$sections, int $index): void;
}
