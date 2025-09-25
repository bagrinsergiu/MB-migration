<?php

namespace MBMigration\Builder\Layout\Theme\Dusk;

use MBMigration\Builder\Layout\Common\AbstractTheme;
use MBMigration\Builder\Layout\Theme\Dusk\SectionHandlers\MiddleMediaTopHandler;

class Dusk extends AbstractTheme
{
    /**
     * @var array|MiddleMediaTopHandler[]
     */
    private array $sectionHandlers;

    /**
     * Constructor method initializes the sectionHandlers property with default handlers.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sectionHandlers = [
            new MiddleMediaTopHandler(),
        ];
    }

    public function getThemeIconSelector(): string
    {
        return "[data-socialicon],[style*=\"font-family: 'Mono Social Icons Font'\"],[data-icon]";
    }

    public function getThemeButtonSelector(): string
    {
        return ".sites-button:not(.nav-menu-button)";
    }

    public function addSectionIfNeeded(array &$mbPageSections)
    {
        foreach ($mbPageSections as $key => $section) {
            foreach ($this->sectionHandlers as $handler) {
                if ($handler->supports($section)) {
                    $handler->handle($mbPageSections, (int)$key);
                    break;
                }
            }
        }
    }

}
