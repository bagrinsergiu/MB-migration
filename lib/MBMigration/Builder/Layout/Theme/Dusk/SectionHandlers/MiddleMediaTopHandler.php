<?php

namespace MBMigration\Builder\Layout\Theme\Dusk\SectionHandlers;

class MiddleMediaTopHandler implements SectionHandlerInterface
{
    public function supports(array $section): bool
    {
        return isset($section['typeSection']) && $section['typeSection'] === 'middle-media';
    }

    public function handle(array &$sections, int $index): void
    {
        $newSection = [
            'sectionId' => $sections[$index]['sectionId'],
            'typeSection' => 'middle-media-top',
            'position' => $sections[$index]['position'],
            'category' => $sections[$index]['category'],
            'settings' => $sections[$index]['settings'],
        ];

        array_splice($sections, $index, 0, [$newSection]);
    }
}
