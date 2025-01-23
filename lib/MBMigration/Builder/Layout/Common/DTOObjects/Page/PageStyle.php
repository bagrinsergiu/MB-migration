<?php

namespace MBMigration\Builder\Layout\Common\DTOObjects\Page;

class PageStyle
{

    public $style;
    private array $sectionLegacyData = [
        'previousSectionEmpty' => false,
    ];

    public function getSectionLegacyData (): array
    {
        return $this->sectionLegacyData;
    }

    public function setPreviousSectionEmpty (bool $isEmpty = false): PageStyle
    {
        $this->sectionLegacyData['previousSectionEmpty'] = $isEmpty;

        return $this;
    }

    public function getPreviousSectionEmpty(): bool
    {
        return $this->sectionLegacyData['previousSectionEmpty'];
    }

}
