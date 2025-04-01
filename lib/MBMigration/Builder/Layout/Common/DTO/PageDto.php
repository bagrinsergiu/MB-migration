<?php

namespace MBMigration\Builder\Layout\Common\DTO;

use MBMigration\Builder\Layout\Common\DTOObjects\Page\PageStyle;
use MBMigration\Builder\Layout\Common\DTOObjects\Button\ButtonStyle;
use MBMigration\Builder\Layout\Common\DTOObjects\Head\HeadStyle;

class PageDto implements DTO
{
    private array $pageStyleDetails = [];
    private ButtonStyle $buttonStyle;
    private HeadStyle $headStyle;
    private PageStyle $pageStyle;

    public function __construct()
    {
        $this->buttonStyle = new ButtonStyle();
        $this->headStyle = new HeadStyle();
        $this->pageStyle = new PageStyle();
    }

    public function setPageStyleDetails(array $pageStyleDetails) {
        $this->pageStyleDetails = array_merge($this->pageStyleDetails, $pageStyleDetails);
    }

    public function getPageStyleDetails(): array
    {
        return $this->pageStyleDetails;
    }

    public function getPageStyle(): PageStyle
    {
        return $this->pageStyle;
    }

    public function getButtonStyle(): ButtonStyle
    {
        return $this->buttonStyle;
    }

    public function getHeadStyle(): HeadStyle
    {
        return $this->headStyle;
    }
}
