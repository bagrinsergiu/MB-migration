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
        string                  $selector = null,
                                $options = null,
        array                   $customStyles = [],
        ?int                    $position = null,
                                $pseudoElement = ':after'
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
                    'pseudoElement' => $pseudoElement
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
                $lineStyles['width'] / 2,
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
                $lineStyles['width'] / 2,
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

}
