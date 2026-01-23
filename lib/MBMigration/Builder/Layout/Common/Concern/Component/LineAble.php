<?php

namespace MBMigration\Builder\Layout\Common\Concern\Component;

use _PHPStan_a2c094651\Symfony\Component\Console\Color;
use Exception;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Core\Logger;

trait LineAble
{
    protected function handleLine(
        ElementContextInterface $data,
        BrowserPageInterface    $browserPage,
        string                  $selectorId = null,
                                $options = null,
        array                   $customStyles = [],
        ?int                    $position = null,
                                $pseudoElement = ':after',
        string                  $selectorPerElement = null
    ): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        try {
            if (isset($selectorPerElement)) {
                $fullSelector = '[data-id=\'' . $selectorId . '\']' . ' ' . $selectorPerElement;
            } else {
                $fullSelector = '[data-id=\'' . $selectorId . '\']';
            }

            $lineStylesD = $browserPage->evaluateScript(
                'brizy.getStyles',
                [
                    'selector' => $fullSelector,
                    'styleProperties' => [
                        'border-top-color',
                        'border-top-style',
                        'border-top-width',
                        'border-bottom-color',
                        'border-bottom-style',
                        'border-bottom-width',
                        'width',
                        'text-align',
                    ],
                    'families' => $data->getFontFamilies(),
                    'defaultFamily' => $data->getDefaultFontFamily(),
                    'pseudoElement' => $pseudoElement
                ]
            );

            $data = $lineStylesD['data'] ?? [];
            $topBorderWidth = (int)($data['border-top-width'] ?? 0);
            $bottomBorderWidth = (int)($data['border-bottom-width'] ?? 0);

            $lineStyles = [
                'color' => ColorConverter::rgba2hex($data['border-bottom-color'] ?? 'rgba(0,0,0,1)'),
                'width' => (int)($data['width'] ?? 0),
                'borderWidth' => max($topBorderWidth, $bottomBorderWidth),
                'align' => $data['text-align'] ?? 'left',
            ];

            $brizySection->addLine(
                $this->getCalculateLineWidth($lineStyles['width']),
                ['color' => $lineStyles['color'], 'opacity' => 1],
                $lineStyles['borderWidth'],
                $customStyles,
                $position,
                $lineStyles['align'] ?? 'center',
            );
        } catch (Exception $e) {
            Logger::instance()->critical($e->getMessage(), [$mbSection['typeSection']]);
        }

        return $brizySection;
    }

    protected function handleLineMediaSection(
        ElementContextInterface $data,
        BrowserPageInterface    $browserPage,
        string                  $selector = null,
                                $options = null,
        array                   $customStyles = [],
        ?int                    $position = null,
                                $align = 'center'
    ): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        try {

            $lineStyles = $browserPage->evaluateScript(
                'brizy.getStyles',
                [
                    'selector' => '[data-id=\'' . $selector . '\']',
                    'styleProperties' => [
                        'border-top-color',
                        'border-top-style',
                        'border-top-width',
                        'border-bottom-color',
                        'border-bottom-style',
                        'border-bottom-width',
                        'width',
                        'text-align',
                    ],
                    'families' => $data->getFontFamilies(),
                    'defaultFamily' => $data->getDefaultFontFamily(),
                    'pseudoElement' => ':after'
                ]
            );

            $topBorderWidth = (int)$lineStyles['data']['border-top-width'];
            $bottomBorderWidth = (int)$lineStyles['data']['border-bottom-width'];

            $lineStyles = [
                'color' => ColorConverter::rgba2hex($lineStyles['data']['border-top-color']),
                'width' => (int)$lineStyles['data']['width'],
                'borderWidth' => max($topBorderWidth, $bottomBorderWidth),
                'align' => $lineStyles['data']['text-align'],
            ];

            $brizySection->addLine(
                $this->getCalculateLineWidth($lineStyles['width']),
                ['color' => $lineStyles['color'], 'opacity' => 1],
                $lineStyles['borderWidth'],
                $customStyles,
                $position,
                $lineStyles['align'] ?? 'center',
            );
        } catch (Exception $e) {
            Logger::instance()->critical($e->getMessage(), [$mbSection['typeSection']]);
        }

        return $brizySection;
    }

    /**
     * @param $width
     * @return float|int
     */
    protected function getCalculateLineWidth($width)
    {
        return $width / 2;
    }

}
