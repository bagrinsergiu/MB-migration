<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use MBMigration\Builder\Layout\Common\Concern\RichTextAble;

/**
 * Minimal Element implementation for Anthem theme to satisfy tests.
 * Exposes detectLinkType publicly and reuses RichTextAble's logic.
 */
class Element
{
    use RichTextAble {
        detectLinkType as private traitDetectLinkType;
    }

    /**
     * Public proxy to trait's detectLinkType for testing and consumers.
     */
    public function detectLinkType($link): string
    {
        return $this->traitDetectLinkType($link);
    }
}
