<?php

namespace MBMigration\Builder\Layout\Common\Template\DetailPages;

use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\DTO\PageDto;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;

class DetailsPage
{
    protected BrizyComponent $detailsSection;
    protected static BrizyComponent $cacheEvent;
    protected static BrizyComponent $cacheSermons;
    protected int $topPaddingOfTheFirstElement;
    protected int $mobileTopPaddingOfTheFirstElement;
    protected array $colorPalettes;
    protected string $subpalette;
    protected PageDto $pageTDO;
    protected array $additionalOptions;

    /**
     * @throws BadJsonProvided
     */
    public function __construct(
        $detailsSection,
        int $topPaddingOfTheFirstElement,
        int $mobileTopPaddingOfTheFirstElement,
        PageDto $pageTDO,
        ElementContextInterface $data,
        string $subpalette = 'subpalette1',
        $additionalOptionsForDetailPage = []
    )
    {
        $this->detailsSection = new BrizyComponent(json_decode($detailsSection, true));
        $this->topPaddingOfTheFirstElement = $topPaddingOfTheFirstElement;
        $this->mobileTopPaddingOfTheFirstElement = $mobileTopPaddingOfTheFirstElement;
        $this->pageTDO = $pageTDO;
        $this->colorPalettes = $data->getThemeContext()->getRootPalettes()->getSubPalettes();
        $this->subpalette = $subpalette;
        $this->additionalOptions = $additionalOptionsForDetailPage;
    }

    protected function rewriteColorIfSetOpacity(array &$colors): void
    {
        foreach ($colors as $key => $color) {
            if (is_array($color) && isset($color['color'], $color['opacity'])) {
                $colors[$key] = $color['color'];
                $colors[$key . '-opacity'] = $color['opacity'];
            }
        }
    }

}
