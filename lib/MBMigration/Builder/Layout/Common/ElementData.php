<?php

namespace MBMigration\Builder\Layout\Common;

use MBMigration\Browser\BrowserPageInterface;

final class ElementData implements ElementDataInterface
{
    /**
     * @var array
     */
    private $mbSection;
    /**
     * @var array
     */
    private $menu;

    /**
     * @param $mbSection
     * @param $browserData
     * @param $menu
     * @return self
     */
    static public function instance(
        array $mbSection,
        array $menu = []
    ): self {
        return new self($mbSection, $menu);
    }

    public function __construct(array $section, array $menu)
    {
        $this->mbSection = $section;
        $this->menu = $menu;
    }

    public function getMbSection(): array
    {
        return $this->mbSection;
    }

    public function getMenu(): array
    {
        return $this->menu;
    }
}