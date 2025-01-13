<?php

namespace MBMigration\Builder\Layout\Common\Elements\Events;

class DetailsPage
{
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
