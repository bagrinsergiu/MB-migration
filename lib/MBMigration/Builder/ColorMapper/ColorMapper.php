<?php

namespace MBMigration\Builder\ColorMapper;

use MBMigration\Core\Utils;
use MBMigration\Builder\ColorMapper\ColorControllerSCSS;

class ColorMapper
{
    /**
     * @throws \Exception
     */
    public function Anthem (array $colorKit): array
    {
        $magicColors = [
            'color7' => ColorControllerSCSS::result('chooseLargerContrast', [$colorKit['color1'], $colorKit['color4'], $colorKit['color5']]),
            'color8' => ColorControllerSCSS::result('chooseLargerContrast', [$colorKit['color5'], $colorKit['color4'], $colorKit['color1']]),
            'color9' => ColorControllerSCSS::result('chooseLargerContrast', [$colorKit['color3'], $colorKit['color4'], $colorKit['color1']]),
            'colorA' => ColorControllerSCSS::result('chooseLargerContrast', [$colorKit['color2'], $colorKit['color3'], $colorKit['color1']])
        ];

        $colorKit = array_merge($colorKit, $magicColors);

        $result = [
            'subpalette1' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['color7'],
                'text'              => ColorControllerSCSS::result('desaturateByPercent', $colorKit['color7'], 50),
                'header'            => $colorKit['color7'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color7'],
                'btn-text'          => ColorControllerSCSS::result('getContrastingColor', $colorKit['color7']),
                'gal-btn'           => ColorControllerSCSS::result('getContrastingColor', $colorKit['color3']),
                'input-border'      => $colorKit['color7'],
                'input-unselected'  => $colorKit['color7'],
                'input-selected'    => $colorKit['color7'],
                'tab-border'        => $colorKit['color7'],
                'tab-text'          => $colorKit['color7'],
                'tab-text-active'   => $colorKit['color6'],
                'accordion-border'  => $colorKit['color7'],
                'accordion-control' => $colorKit['color6']
            ],
            'subpalette2' => [
                'bg'                => $colorKit['color5'],
                'accent'            => $colorKit['color8'],
                'text'              => ColorControllerSCSS::result('desaturateByPercent', $colorKit['color8'],50),
                'header'            => $colorKit['color8'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color2'],
                'btn-text'          => ColorControllerSCSS::result('getContrastingColor', $colorKit['color2']),
                'gal-btn'           => ColorControllerSCSS::result('getContrastingColor', $colorKit['color1']),
                'input-border'      => $colorKit['color2'],
                'input-unselected'  => $colorKit['color2'],
                'input-selected'    => $colorKit['color2'],
                'tab-border'        => $colorKit['color8'],
                'tab-text'          => $colorKit['color8'],
                'tab-text-active'   => $colorKit['color6'],
                'accordion-border'  => $colorKit['color8'],
                'accordion-control' => $colorKit['color6']
            ],
            'subpalette3' => [
                'bg'                => $colorKit['color2'],
                'accent'            => $colorKit['colorA'],
                'text'              => ColorControllerSCSS::result('desaturateByPercent', $colorKit['colorA'],50),
                'header'            => $colorKit['colorA'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['colorA'],
                'btn-text'          => $colorKit['color2'],
                'gal-btn'           => ColorControllerSCSS::result('getContrastingColor', $colorKit['color2']),
                'input-border'      => $colorKit['colorA'],
                'input-unselected'  => $colorKit['colorA'],
                'input-selected'    => $colorKit['colorA'],
                'tab-border'        => $colorKit['colorA'],
                'tab-text'          => $colorKit['colorA'],
                'tab-text-active'   => $colorKit['color6'],
                'accordion-border'  => $colorKit['colorA'],
                'accordion-control' => $colorKit['color6']
            ],
            'subpalette4' => [
                'bg'                => $colorKit['color3'],
                'accent'            => $colorKit['color9'],
                'text'              => ColorControllerSCSS::result('desaturateByPercent', $colorKit['color9'],50),
                'header'            => $colorKit['color9'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color2'],
                'btn-text'          => ColorControllerSCSS::result('getContrastingColor', $colorKit['color2']),
                'gal-btn'           => ColorControllerSCSS::result('getContrastingColor', $colorKit['color3']),
                'input-border'      => $colorKit['color2'],
                'input-unselected'  => $colorKit['color2'],
                'input-selected'    => $colorKit['color2'],
                'tab-border'        => $colorKit['color9'],
                'tab-text'          => $colorKit['color9'],
                'tab-text-active'   => $colorKit['color6'],
                'accordion-border'  => $colorKit['color9'],
                'accordion-control' => $colorKit['color6']
            ],
            'nav-subpalette' => [
                'bg'                => $colorKit['color1'],
                'nav-bg'            => $colorKit['color1'],
                'sub-bg'            => $colorKit['color3'],
                'nav-text'          => ColorControllerSCSS::result('mixContrastingColor', $colorKit['color1'],24),
                'sub-text'          => ColorControllerSCSS::result('mixContrastingColor', $colorKit['color3'],24),
                'nav-acc'           => ColorControllerSCSS::result('mixContrastingColor', $colorKit['color1'],0),
                'sub-acc'           => ColorControllerSCSS::result('mixContrastingColor', $colorKit['color3'],0)
            ]
        ];

        return $result;
    }

    private function August (array $colorKit): array
    {
        $magicColors = [
            'color7' => $this->chooseLargerContrast($colorKit['color1'],$colorKit['color3'],$colorKit['color5']),
            'color8' => $this->chooseLargerContrast($colorKit['color2'],$colorKit['color5'],$colorKit['color1']),
            'color9' => $this->chooseLargerContrast($colorKit['color3'],$colorKit['color5'],$colorKit['color1'])
        ];

        $colorKit = array_merge($colorKit, $magicColors);

        return [
            'subpalette1' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['color7'],
                'text'              => $colorKit['color7'],
                'header'            => $colorKit['color7'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6'],
                'gal-btn'           => '#fff'
            ],
            'subpalette2' => [
                'bg'                => $colorKit['color2'],
                'accent'            => $colorKit['color8'],
                'text'              => $colorKit['color8'],
                'header'            => $colorKit['color8'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6'],
                'gal-btn'           => '#2a2a2a'
            ],
            'subpalette3' => [
                'bg'                => $colorKit['color3'],
                'accent'            => $colorKit['color9'],
                'text'              => $colorKit['color9'],
                'header'            => $colorKit['color9'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6'],
                'gal-btn'           => '#fff'
            ],
            'subpalette4' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['color7'],
                'text'              => $colorKit['color7'],
                'header'            => $colorKit['color7'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color7'],
                'gal-btn'           => '#2a2a2a'
            ]
        ];
    }

    private function Aurora (array $colorKit): array
    {
        $magicColors = [
            'color7' => $this->chooseLargerContrast($colorKit['color1'],$colorKit['color3'],$colorKit['color5']),
            'color8' => $this->chooseLargerContrast($colorKit['color2'],$colorKit['color5'],$colorKit['color3']),
            'color9' => $this->chooseLargerContrast($colorKit['color1'],$colorKit['color5'],$colorKit['color3']),
            'colorA' => $this->chooseLargerContrast($colorKit['color3'],$colorKit['color5'],$colorKit['color1']),
            'colorB' => $this->chooseLesserContrast($colorKit['color1'],$colorKit['color3'],$colorKit['color5']),
            'colorC' => $this->chooseLesserContrast($colorKit['color2'],$colorKit['color3'],$colorKit['color5']),
            'colorD' => $this->chooseLesserContrast($colorKit['color1'],$colorKit['color5'],$colorKit['color3']),
            'colorE' => $this->chooseLesserContrast($colorKit['color3'],$colorKit['color1'],$colorKit['color5']),
        ];

        $colorKit = array_merge($colorKit, $magicColors);

        return [
            'subpalette1' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['colorB'],
                'text'              => $colorKit['color7'],
                'header'            => $colorKit['color7'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6'],
                'footer'            => $colorKit['colorH'],
                'gal-btn'           => '#fff'
            ],
            'subpalette2' => [
                'bg'                => $colorKit['color2'],
                'accent'            => $colorKit['colorC'],
                'text'              => $colorKit['color8'],
                'header'            => $colorKit['color8'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6'],
                'footer'            => $colorKit['colorH'],
                'gal-btn'           => '#2a2a2a'
            ],
            'subpalette3' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['colorB'],
                'text'              => $colorKit['color7'],
                'header'            => $colorKit['color7'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color9'],
                'footer'            => $colorKit['colorH'],
                'gal-btn'           => '#fff'
            ],
            'subpalette4' => [
                'bg'                => $colorKit['color3'],
                'accent'            => $colorKit['colorE'],
                'text'              => $colorKit['colorA'],
                'header'            => $colorKit['colorA'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['colorA'],
                'footer'            => $colorKit['colorH'],
                'gal-btn'           => '#2a2a2a'
            ]
        ];
    }

    private function Bloom (array $colorKit): array
    {
        $magicColors = [
            'color7' => $this->chooseLargerContrast($colorKit['color1'],$colorKit['color4'],$colorKit['color5']),
            'color8' => $this->chooseLargerContrast($colorKit['color4'],$colorKit['color5'],$colorKit['color1']),
            'color9' => $this->chooseLargerContrast($colorKit['color3'],$colorKit['color5'],$colorKit['color1']),
            'colorA' => $this->chooseLargerContrast($colorKit['color2'],$colorKit['color5'],$colorKit['color1'])
        ];

        $colorKit = array_merge($colorKit, $magicColors);

        return [
            'subpalette1' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['color5'],
                'text'              => $colorKit['color7'],
                'header'            => $colorKit['color7'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color7'],
                'gallery-btn'       => $colorKit['color6'],
                'gallery-acc'       => $colorKit['color7'],
                'input-border'      => $colorKit['color7'],
                'input-unselected'  => $colorKit['color7'],
                'input-selected'    => $colorKit['color7']
            ],
            'subpalette2' => [
                'bg'                => $colorKit['color4'],
                'accent'            => $colorKit['color5'],
                'text'              => $colorKit['color8'],
                'header'            => $colorKit['color8'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color8'],
                'gallery-btn'       => $colorKit['color8'],
                'gallery-acc'       => $colorKit['color6'],
                'input-border'      => $colorKit['color8'],
                'input-unselected'  => $colorKit['color8'],
                'input-selected'    => $colorKit['color8']
            ],
            'subpalette3' => [
                'bg'                => $colorKit['color2'],
                'accent'            => $colorKit['color5'],
                'text'              => $colorKit['colorA'],
                'header'            => $colorKit['colorA'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['colorA'],
                'gallery-btn'       => $colorKit['colorA'],
                'gallery-acc'       => $colorKit['color6'],
                'input-border'      => $colorKit['colorA'],
                'input-unselected'  => $colorKit['colorA'],
                'input-selected'    => $colorKit['colorA']
            ],
            'subpalette4' => [
                'bg'                => $colorKit['color3'],
                'accent'            => $colorKit['color5'],
                'text'              => $colorKit['color9'],
                'header'            => $colorKit['color9'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color9'],
                'gallery-btn'       => $colorKit['color9'],
                'gallery-acc'       => $colorKit['color6'],
                'input-border'      => $colorKit['color9'],
                'input-unselected'  => $colorKit['color9'],
                'input-selected'    => $colorKit['color9']
            ]
        ];
    }

    private function Boulevard (array $colorKit): array
    {
        $magicColors = [
            'colorA' => $this->chooseLargerContrast($colorKit['color1'], $colorKit['color3'], $colorKit['color5']),
            'colorB' => $this->chooseLargerContrast($colorKit['color2'], $colorKit['color3'], $colorKit['color5']),
            'colorC' => $this->chooseLargerContrast($colorKit['color3'], $colorKit['color1'], $colorKit['color5']),
            'colorD' => $this->chooseLargerContrast($colorKit['color4'], $colorKit['color1'], $colorKit['color5']),
            'colorE' => $this->chooseLargerContrast($colorKit['color3'],
                $this->lighten($colorKit['color3'], 10),
                $this->darken($colorKit['color3'], 9))
        ];

        $colorKit = array_merge($colorKit, $magicColors);
        return [
            'subpalette1' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['colorA'],
                'text'              => $colorKit['colorA'],
                'header'            => $colorKit['colorA'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6']
            ],
            'subpalette2' => [
                'bg'                => $colorKit['color2'],
                'accent'            => $colorKit['colorB'],
                'text'              => $colorKit['colorB'],
                'header'            => $colorKit['colorB'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6']
            ],
            'subpalette3' => [
                'bg'                => $colorKit['color3'],
                'accent'            => $colorKit['colorC'],
                'text'              => $colorKit['colorC'],
                'header'            => $colorKit['colorC'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color4']
            ],
            'subpalette4' => [
                'bg'                => $colorKit['color4'],
                'accent'            => $colorKit['colorD'],
                'text'              => $colorKit['colorD'],
                'header'            => $colorKit['colorD'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['colorD']
            ]
        ];
    }

    private function Dusk (array $colorKit): array
    {
        return [
            'subpalette1' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['color2'],
                'text'              => $colorKit['color3'],
                'header'            => $colorKit['color3'],
                'link'              => $colorKit['color2'],
                'btn'               => $colorKit['color2']
            ],
            'subpalette2' => [
                'bg'                => $colorKit['color3'],
                'accent'            => $colorKit['color2'],
                'text'              => $colorKit['color1'],
                'header'            => $colorKit['color1'],
                'link'              => $colorKit['color2'],
                'btn'               => $colorKit['color1']
            ],
            'subpalette3' => [
                'bg'                => $colorKit['color2'],
                'accent'            => $colorKit['color1'],
                'text'              => $colorKit['color3'],
                'header'            => $colorKit['color3'],
                'link'              => $colorKit['color1'],
                'btn'               => $colorKit['color1']
            ],
            'subpalette4' => [
                'bg'                => $colorKit['color4'],
                'accent'            => $colorKit['color5'],
                'text'              => $colorKit['color1'],
                'header'            => $colorKit['color1'],
                'link'              => $colorKit['color5'],
                'btn'               => $colorKit['color5']
            ]
        ];
    }

    private function Ember (array $colorKit): array
    {
        return [
            'subpalette1' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['color2'],
                'text'              => $colorKit['color3'],
                'header'            => $colorKit['color3'],
                'link'              => $colorKit['color4'],
                'btn'               => $colorKit['color4']
            ],
            'subpalette2' => [
                'bg'                => $colorKit['color3'],
                'accent'            => $colorKit['color2'],
                'text'              => $colorKit['color1'],
                'header'            => $colorKit['color1'],
                'link'              => $colorKit['color2'],
                'btn'               => $colorKit['color4']
            ],
            'subpalette3' => [
                'bg'                => $colorKit['color5'],
                'accent'            => $colorKit['color2'],
                'text'              => $colorKit['color3'],
                'header'            => $colorKit['color3'],
                'link'              => $colorKit['color4'],
                'btn'               => $colorKit['color4']
            ],
            'subpalette4' => [
                'bg'                => $colorKit['color4'],
                'accent'            => $colorKit['color2'],
                'text'              => $colorKit['color1'],
                'header'            => $colorKit['color1'],
                'link'              => $colorKit['color2'],
                'btn'               => $colorKit['color2']
            ]
        ];
    }

    private function Hope (array $colorKit): array
    {
        $magicColors = [
            'color7' => $this->chooseLargerContrast($colorKit['color1'],$colorKit['color4'],$colorKit['color5']),
            'color8' => $this->chooseLargerContrast($colorKit['color2'],$colorKit['color5'],$colorKit['color1']),
            'color9' => $this->chooseLargerContrast($colorKit['color1'],$colorKit['color5'],$colorKit['color4']),
            'colorA' => $this->chooseLargerContrast($colorKit['color4'],$colorKit['color5'],$colorKit['color1']),
        ];

        $colorKit = array_merge($colorKit, $magicColors);

        return [
            'subpalette1' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['color6'],
                'text'              => $colorKit['color7'],
                'header'            => $colorKit['color7'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6']
            ],
            'subpalette2' => [
                'bg'                => $colorKit['color2'],
                'accent'            => $colorKit['color6'],
                'text'              => $colorKit['color8'],
                'header'            => $colorKit['color8'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6']
            ],
            'subpalette3' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['color6'],
                'text'              => $colorKit['color7'],
                'header'            => $colorKit['color7'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color7']
            ],
            'subpalette4' => [
                'bg'                => $colorKit['color4'],
                'accent'            => $colorKit['color6'],
                'text'              => $colorKit['colorA'],
                'header'            => $colorKit['colorA'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6']
            ]
        ];
    }

    private function Majesty (array $colorKit): array
    {
        $magicColors = [
            'color7' => $this->chooseLargerContrast($colorKit['color1'],$colorKit['color4'],$colorKit['color5']),
            'color8' => $this->chooseLargerContrast($colorKit['color5'],$colorKit['color4'],$colorKit['color1']),
            'color9' => $this->chooseLargerContrast($colorKit['color3'],$colorKit['color4'],$colorKit['color1']),
            'colorA' => $this->chooseLargerContrast($colorKit['color2'],$colorKit['color3'],$colorKit['color1']),
        ];

        $colorKit = array_merge($colorKit, $magicColors);

        return [
            'subpalette1' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['color6'],
                'text'              => $colorKit['color7'],
                'header'            => $colorKit['color7'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6']
            ],
            'subpalette2' => [
                'bg'                => $colorKit['color2'],
                'accent'            => $colorKit['color6'],
                'text'              => $colorKit['color8'],
                'header'            => $colorKit['color8'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6']
            ],
            'subpalette3' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['color6'],
                'text'              => $colorKit['color7'],
                'header'            => $colorKit['color7'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color7']
            ],
            'subpalette4' => [
                'bg'                => $colorKit['color4'],
                'accent'            => $colorKit['color6'],
                'text'              => $colorKit['colorA'],
                'header'            => $colorKit['colorA'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6']
            ]
        ];
    }

    private function Serene (array $colorKit): array
    {
        return [
            'subpalette1' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['color2'],
                'text'              => $this->getContrastingColor($colorKit['color1']),
                'header'            => $this->getContrastingColor($colorKit['color1']),
                'link'              => $colorKit['color2'],
                'btn'               => $colorKit['color2']
            ],
            'subpalette2' => [
                'bg'                => $colorKit['color4'],
                'accent'            => $colorKit['color6'],
                'text'              => $this->getContrastingColor($colorKit['color4']),
                'header'            => $this->getContrastingColor($colorKit['color4']),
                'link'              => $colorKit['color2'],
                'btn'               => $colorKit['color2']
            ],
            'subpalette3' => [
                'bg'                => $colorKit['color5'],
                'accent'            => $colorKit['color2'],
                'text'              => $this->getContrastingColor($colorKit['color5']),
                'header'            => $this->getContrastingColor($colorKit['color5']),
                'link'              => $colorKit['color4'],
                'btn'               => $colorKit['color2']
            ],
            'subpalette4' => [
                'bg'                => $colorKit['color3'],
                'accent'            => $colorKit['color2'],
                'text'              => $this->getContrastingColor($colorKit['color3']),
                'header'            => $this->getContrastingColor($colorKit['color3']),
                'link'              => $colorKit['color4'],
                'btn'               => $colorKit['color2']
            ]
        ];
    }

    private function Solstice (array $colorKit): array
    {
        $magicColors = [
            'colorA' => $this->chooseLargerContrast($colorKit['color1'],$colorKit['color5'],$colorKit['color2']),
            'colorB' => $this->chooseLargerContrast($colorKit['color2'],$colorKit['color5'],$colorKit['color1']),
            'colorC' => $this->chooseLargerContrast($colorKit['color3'],$colorKit['color5'],$colorKit['color1']),
            'colorD' => $this->chooseLargerContrast($colorKit['color4'],$colorKit['color5'],$colorKit['color1']),
            'colorE' => $this->chooseLargerContrast($colorKit['color3'],$colorKit['color5'],$colorKit['color1']),
        ];

        $colorKit = array_merge($colorKit, $magicColors);

        return [
            'subpalette1' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['color1'],
                'text'              => $colorKit['colorA'],
                'header'            => $colorKit['colorA'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6']
            ],
            'subpalette2' => [
                'bg'                => $colorKit['color2'],
                'accent'            => $colorKit['color1'],
                'text'              => $colorKit['colorB'],
                'header'            => $colorKit['colorB'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6']
            ],
            'subpalette3' => [
                'bg'                => $colorKit['color3'],
                'accent'            => $colorKit['color1'],
                'text'              => $colorKit['colorC'],
                'header'            => $colorKit['colorC'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color4']
            ],
            'subpalette4' => [
                'bg'                => $colorKit['color4'],
                'accent'            => $colorKit['color1'],
                'text'              => $colorKit['colorD'],
                'header'            => $colorKit['colorD'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['colorD']
            ]
        ];
    }

    private function Tradition (array $colorKit): array
    {
        return [
            'subpalette1' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['color4'],
                'text'              => $colorKit['color3'],
                'header'            => $colorKit['color3'],
                'link'              => $colorKit['color2'],
                'btn'               => $colorKit['color4']
            ],
            'subpalette2' => [
                'bg'                => $colorKit['color3'],
                'accent'            => $colorKit['color4'],
                'text'              => $colorKit['color1'],
                'header'            => $colorKit['color1'],
                'link'              => $colorKit['color2'],
                'btn'               => $colorKit['color4']
            ],
            'subpalette3' => [
                'bg'                => $colorKit['color2'],
                'accent'            => $colorKit['color1'],
                'text'              => $colorKit['color1'],
                'header'            => $colorKit['color1'],
                'link'              => $colorKit['color3'],
                'btn'               => $colorKit['color3']
            ],
            'subpalette4' => [
                'bg'                => $colorKit['color4'],
                'accent'            => $colorKit['color1'],
                'text'              => $colorKit['color1'],
                'header'            => $colorKit['color1'],
                'link'              => $colorKit['color3'],
                'btn'               => $colorKit['color3']
            ]
        ];
    }

    private function Voyage (array $colorKit): array
    {
        $magicColors = [
            'color7' => $this->chooseLargerContrast($colorKit['color1'],
                $this->chooseLargerContrast($colorKit['color1'],$colorKit['color2'],$colorKit['color3']),
                $this->chooseLargerContrast($colorKit['color1'],$colorKit['color4'],$colorKit['color5'])),
            'color8' =>$this->chooseLargerContrast($colorKit['color2'],
                $this->chooseLargerContrast($colorKit['color2'],$colorKit['color1'],$colorKit['color3']),
                $this->chooseLargerContrast($colorKit['color2'],$colorKit['color4'],$colorKit['color5'])),
            'color9' => $this->chooseLargerContrast($colorKit['color3'],
                $this->chooseLargerContrast($colorKit['color3'],$colorKit['color2'],$colorKit['color1']),
                $this->chooseLargerContrast($colorKit['color3'],$colorKit['color4'],$colorKit['color5'])),
            'colorA' => $this->chooseLargerContrast($colorKit['color4'],
                $this->chooseLargerContrast($colorKit['color4'],$colorKit['color2'],$colorKit['color3']),
                $this->chooseLargerContrast($colorKit['color4'],$colorKit['color1'],$colorKit['color5'])),
            'colorB' => $this->chooseLargerContrast($colorKit['color5'],
                $this->chooseLargerContrast($colorKit['color5'],$colorKit['color1'],$colorKit['color2']),
                $this->chooseLargerContrast($colorKit['color5'],$colorKit['color3'],$colorKit['color4'])),
        ];

        $colorKit = array_merge($colorKit, $magicColors);

        return [
            'subpalette1' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['color3'],
                'text'              => $colorKit['color7'],
                'header'            => $colorKit['color7'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6']
            ],
            'subpalette2' => [
                'bg'                => $colorKit['color2'],
                'accent'            => $colorKit['color3'],
                'text'              => $colorKit['color8'],
                'header'            => $colorKit['color8'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6']
            ],
            'subpalette3' => [
                'bg'                => $colorKit['color3'],
                'accent'            => $colorKit['color1'],
                'text'              => $colorKit['color9'],
                'header'            => $colorKit['color9'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6']
            ],
            'subpalette4' => [
                'bg'                => $colorKit['color4'],
                'accent'            => $colorKit['color3'],
                'text'              => $colorKit['colorA'],
                'header'            => $colorKit['colorA'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color6']
            ]
        ];
    }

    private function Zion (array $colorKit): array
    {
        $magicColors = [
            'color7' => $this->chooseLargerContrast($colorKit['color1'],$colorKit['color5'],$colorKit['color2']),
            'color8' => $this->chooseLargerContrast($colorKit['color2'],$colorKit['color5'],$colorKit['color1']),
            'color9' => $this->chooseLargerContrast($colorKit['color3'],$colorKit['color5'],$colorKit['color1']),
            'colorA' => $this->chooseLargerContrast($colorKit['color4'],$colorKit['color5'],$colorKit['color1'])
        ];

        $colorKit = array_merge($colorKit, $magicColors);

        $magicColors = [
            'colorB' => $this->chooseLesserContrast($colorKit['color1'],$colorKit['color4'],$colorKit['color7']),
            'colorC' => $this->chooseLesserContrast($colorKit['color2'],$colorKit['color4'],$colorKit['color8']),
            'colorD' => $this->chooseLesserContrast($colorKit['color3'],$colorKit['color4'],$colorKit['color9']),
            'colorE' => $this->chooseLesserContrast($colorKit['color4'],$colorKit['color1'],$colorKit['colorA'])
        ];

        $colorKit = array_merge($colorKit, $magicColors);

        return [
            'subpalette1' => [
                'bg'                => $colorKit['color1'],
                'accent'            => $colorKit['colorB'],
                'text'              => $colorKit['color7'],
                'header'            => $colorKit['color7'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color7']
            ],
            'subpalette2' => [
                'bg'                => $colorKit['color2'],
                'accent'            => $colorKit['colorC'],
                'text'              => $colorKit['color8'],
                'header'            => $colorKit['color8'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color4']
            ],
            'subpalette3' => [
                'bg'                => $colorKit['color3'],
                'accent'            => $colorKit['colorD'],
                'text'              => $colorKit['color9'],
                'header'            => $colorKit['color9'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['color4']
            ],
            'subpalette4' => [
                'bg'                => $colorKit['color4'],
                'accent'            => $colorKit['colorE'],
                'text'              => $colorKit['colorA'],
                'header'            => $colorKit['colorA'],
                'link'              => $colorKit['color6'],
                'btn'               => $colorKit['colorA']
            ]
        ];
    }

//    private function chooseLargerContrast($color1, $color2, $color3): string
//    {
//        $brightness1 = $this->calculateBrightness($color1);
//        $brightness2 = $this->calculateBrightness($color2);
//        $brightness3 = $this->calculateBrightness($color3);
//
//        if ($brightness1 >= $brightness2 && $brightness1 >= $brightness3) {
//            return $color1;
//        } elseif ($brightness2 >= $brightness1 && $brightness2 >= $brightness3) {
//            return $color2;
//        } else {
//            return $color3;
//        }
//    }

//    private function chooseLesserContrast($color1, $color2, $color3): string
//    {
//        $brightness1 = $this->calculateBrightness($color1);
//        $brightness2 = $this->calculateBrightness($color2);
//        $brightness3 = $this->calculateBrightness($color3);
//
//        if ($brightness1 <= $brightness2 && $brightness1 <= $brightness3) {
//            return $color1;
//        } elseif ($brightness2 <= $brightness1 && $brightness2 <= $brightness3) {
//            return $color2;
//        } else {
//            return $color3;
//        }
//    }

//    private function calculateBrightness($color)
//    {
//        $red = hexdec(substr($color, 1, 2));
//        $green = hexdec(substr($color, 3, 2));
//        $blue = hexdec(substr($color, 5, 2));
//        return ($red * 299 + $green * 587 + $blue * 114) / 1000;
//    }



    function strip_unit($value) {
        return $value / ($value * 0 + 1);
    }

    function luminance($color) {
        return (color_luminance($color) + .05) * 100;
    }

    function color_luminance($color) {
        $rgb = hexToRGB($color);

        $a = array($rgb[0]/255, $rgb[1]/255, $rgb[2]/255);

        foreach($a as $k => $v) {
            if ($v <= 0.03928) {
                $a[$k] = $v/12.92;
            } else {
                $a[$k] = pow((($v+0.055)/1.055), 2.4);
            }
        }

        $luminance = 0.2126*$a[0] + 0.7152*$a[1] + 0.0722*$a[2];
        return $luminance;
    }

    function chooseLargerContrast($base, $option1, $option2) {
        $baseLumLight = lumLight($base);
        $option1LumLight = lumLight($option1);
        $option2LumLight = lumLight($option2);

        $option1Contrast = abs($baseLumLight - $option1LumLight);
        $option2Contrast = abs($baseLumLight - $option2LumLight);

        if ($option1Contrast > $option2Contrast) {
            return $option1;
        } else {
            return $option2;
        }
    }

    function chooseLesserContrast($base, $option1, $option2) {
        $baseLumLight = lumLight($base);
        $option1LumLight = lumLight($option1);
        $option2LumLight = lumLight($option2);

        $option1Contrast = abs($baseLumLight - $option1LumLight);
        $option2Contrast = abs($baseLumLight - $option2LumLight);

        if ($option1Contrast > $option2Contrast) {
            return $option2;
        } else {
            return $option1;
        }
    }


    private function darken($color, $amount): string
    {
        $amount = max(min($amount, 255), 0);

        $red = hexdec(substr($color, 1, 2));
        $green = hexdec(substr($color, 3, 2));
        $blue = hexdec(substr($color, 5, 2));

        $darkenedRed = max($red - $amount, 0);
        $darkenedGreen = max($green - $amount, 0);
        $darkenedBlue = max($blue - $amount, 0);

        return sprintf("#%02X%02X%02X", $darkenedRed, $darkenedGreen, $darkenedBlue);
    }

    private function lighten($color, $amount): string
    {
        $amount = max(min($amount, 255), 0);

        $red = hexdec(substr($color, 1, 2));
        $green = hexdec(substr($color, 3, 2));
        $blue = hexdec(substr($color, 5, 2));

        $lightenedRed = min($red + $amount, 255);
        $lightenedGreen = min($green + $amount, 255);
        $lightenedBlue = min($blue + $amount, 255);

        return sprintf("#%02X%02X%02X", $lightenedRed, $lightenedGreen, $lightenedBlue);
    }

    private function getContrastingColor($color): string
    {
        $red = hexdec(substr($color, 1, 2));
        $green = hexdec(substr($color, 3, 2));
        $blue = hexdec(substr($color, 5, 2));

        $brightness = ($red * 299 + $green * 587 + $blue * 114) / 1000;

        if ($brightness > 127) {
            $contrastColor = '#000000';
        } else {
            $contrastColor = '#FFFFFF';
        }
        return $contrastColor;
    }

    public function getPalette (string $design, array $colorKit)
    {
        if (method_exists($this, $design)) {
            \MBMigration\Core\Logger::instance()->info('Call method ' . $design);
            return call_user_func_array(array($this, $design), [$colorKit]);
        }
        \MBMigration\Core\Logger::instance()->warning('Method ' . $design . ' does not exist');
        return false;
    }
}