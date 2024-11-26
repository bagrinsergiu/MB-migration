<?php

namespace MBMigration\Builder\Layout\Common\DTO;

class PageDto implements DTO
{
    private array $pageStyleDetails;

    public function setPageStyleDetails(array $pageStyleDetails) {
        $this->pageStyleDetails = $pageStyleDetails;
    }

    public function getPageStyleDetails(): array
    {
        return $this->pageStyleDetails;
    }

}
