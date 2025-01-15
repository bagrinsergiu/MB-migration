<?php

namespace MBMigration\Builder\Layout\Common\DTO;

use MBMigration\Builder\Layout\Common\Objects\Button\ButtonStyle;
use MBMigration\Builder\Layout\Common\Objects\Head\HeadStyle;

class PageDto implements DTO
{
    private array $pageStyleDetails;
    private ButtonStyle $buttonStyle;
    private HeadStyle $headStyle;

    public function __construct()
    {
        $this->buttonStyle = new ButtonStyle();
        $this->headStyle = new HeadStyle();
    }

    public function setPageStyleDetails(array $pageStyleDetails) {
        $this->pageStyleDetails = $pageStyleDetails;
    }

    public function getPageStyleDetails(): array
    {
        return $this->pageStyleDetails;
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
