<?php

namespace MBMigration\Builder\Layout\Common\DTO;

use MBMigration\Builder\Layout\Common\Objects\Button\ButtonStyle;

class PageDto implements DTO
{
    private array $pageStyleDetails;
    private ButtonStyle $buttonStyle;

    public function __construct()
    {
        $this->buttonStyle = new ButtonStyle();
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
}
