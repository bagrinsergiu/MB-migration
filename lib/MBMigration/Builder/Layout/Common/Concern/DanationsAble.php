<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use Exception;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BrizyKitNotFound;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorConverter;

trait DanationsAble
{

    /**
     * Process and add all items the same brizy section
     */
    protected function handleDonations(
        ElementContextInterface $data,
        BrowserPageInterface $browserPage,
        array $brizyKit
    ): BrizyComponent {

        $mbSection = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        if (!isset($brizyKit['donation-button'])) {
            throw new BrizyKitNotFound('The BrizyKit does not contain the key: donation-button');
        }

        try {
            switch ($mbSection['category']) {
                case "donation":
                    $selector = '[data-id="'.$mbSection['sectionId'].'"] button.sites-button';
                    $brizyDonationButton = new BrizyComponent(json_decode($brizyKit['donation-button'], true));
                    $brizyDonationButton = $this->setButtonStyles(
                        $brizyDonationButton,
                        $browserPage,
                        $selector,
                        $data,
                        $mbSection
                    );

                    $brizyDonationButton = $this->setHoveButtonStyles(
                        $brizyDonationButton,
                        $browserPage,
                        $selector,
                        $data,
                        $mbSection
                    );

                    $brizySection->getValue()->add_items([$brizyDonationButton]);
                    break;
            }
        } catch (Exception $e) {

        }


        return $brizySection;
    }

    /**
     * @param BrowserPageInterface $browserPage
     * @param string $selector
     * @param ElementContextInterface $data
     * @param $donationButton
     * @param array $mbSection
     * @return BrizyComponent
     * @throws BrowserScriptException
     */
    protected function setButtonStyles(
        BrizyComponent $brizyDonationButton,
        BrowserPageInterface $browserPage,
        string $selector,
        ElementContextInterface $data,
        array $mbSection
    ): BrizyComponent {
        $buttonStyles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $selector,
                'STYLE_PROPERTIES' => [
                    'font-family',
                    'font-size',
                    'font-weight',
                    'font-style',
                    'letter-spacing',
                    'text-transform',
                    'border-style',
                    'border-width',
                    'padding-top',
                    'padding-bottom',
                    'padding-right',
                    'padding-left',
                    'margin-top',
                    'margin-bottom',
                    'margin-left',
                    'margin-right',
                    'color',
                    'border-top-color',
                    'border-top-style',
                    'border-bottom-left-radius',
                    'border-bottom-right-radius',
                    'border-top-left-radius',
                    'border-top-right-radius',
                    'background-color',
                ],
                'FAMILIES' => $data->getFontFamilies(),
                'DEFAULT_FAMILY' => $data->getDefaultFontFamily(),
            ]
        );

        if (empty($buttonStyles)) {
            throw new BrowserScriptException("The element with selector {$selector} was not found in page.");
        }

        if (isset($buttonStyles['error'])) {
            throw new BrowserScriptException($buttonStyles['error']);
        }
        $buttonStyles = $buttonStyles['data'];

        if (isset($mbSection['settings']['sections']['donations']['alignment']) && $mbSection['settings']['sections']['donations']['alignment'] != '') {
            $brizyDonationButton->getValue()->set_horizontalAlign(
                $mbSection['settings']['sections']['donations']['alignment']
            );
        }

        $brizyDonationButton->getItemValueWithDepth(0)
            ->set_text($mbSection['settings']['sections']['donations']['text'] ?? 'MAKE A DONATION')
            ->set_linkExternal($mbSection['settings']['sections']['donations']['url'] ?? '#')
            ->set_paddingType('ungrouped')
            ->set_paddingTop((int)$buttonStyles['padding-top'])
            ->set_paddingBottom((int)$buttonStyles['padding-bottom'])
            ->set_paddingRight((int)$buttonStyles['padding-right'])
            ->set_paddingLeft((int)$buttonStyles['padding-left'])
            ->set_marginType('ungrouped')
            ->set_marginLeft((int)$buttonStyles['margin-left'])
            ->set_marginRight((int)$buttonStyles['margin-right'])
            ->set_marginTop((int)$buttonStyles['margin-top'])
            ->set_marginBottom((int)$buttonStyles['margin-bottom'])
            ->set_borderRadius((int)$buttonStyles['border-bottom-left-radius'])
            ->set_borderStyle($buttonStyles['border-top-style'])
            ->set_borderColorHex(ColorConverter::rgba2hex($buttonStyles['border-top-color']))
            ->set_borderColorOpacity(ColorConverter::rgba2opacity($buttonStyles['border-top-color']))
            ->set_bgColorOpacity(ColorConverter::rgba2opacity($buttonStyles['background-color']))
            ->set_bgColorHex(ColorConverter::rgba2hex($buttonStyles['background-color']))
            ->set_colorHex(ColorConverter::rgba2hex($buttonStyles['color']))
            ->set_colorOpacity(ColorConverter::rgba2opacity($buttonStyles['color']));

        return $brizyDonationButton;
    }

    protected function setHoveButtonStyles(
        BrizyComponent $brizyDonationButton,
        BrowserPageInterface $browserPage,
        string $selector,
        ElementContextInterface $data,
        array $mbSection
    ): BrizyComponent {
        $browserPage->triggerEvent('hover', $selector);
        $buttonStyles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $selector,
                'styleProperties' => [
                    'font-family',
                    'font-size',
                    'font-weight',
                    'font-style',
                    'letter-spacing',
                    'text-transform',
                    'border-style',
                    'color',
                    'border-top-color',
                    'border-top-style',
                    'background-color',
                ],
                'families' => $data->getFontFamilies(),
                'defaultFamily' => $data->getDefaultFontFamily(),
            ]
        );

        if (isset($buttonStyles['error'])) {
            throw new BrowserScriptException($buttonStyles['error']);
        }
        $buttonStyles = $buttonStyles['data'];

        if (isset($mbSection['settings']['sections']['donations']['alignment'])) {
            $brizyDonationButton->getValue()->set_horizontalAlign(
                $mbSection['settings']['sections']['donations']['alignment']
            );
        }

        $brizyDonationButton->getItemValueWithDepth(0)
            ->set_hoverBgColorHex(ColorConverter::rgba2hex($buttonStyles['background-color']))
            ->set_hoverBgColorOpacity(ColorConverter::rgba2opacity($buttonStyles['background-color']))
            ->set_hoverBorderColorHex(ColorConverter::rgba2hex($buttonStyles['border-top-color']))
            ->set_hoverBorderColorOpacity(ColorConverter::rgba2opacity($buttonStyles['color']))
            ->set_hoverBorderColorHex(ColorConverter::rgba2hex($buttonStyles['color']))
            ->set_hoverColorOpacity(ColorConverter::rgba2opacity($buttonStyles['color']))
            ->set_hoverColorHex(ColorConverter::rgba2hex($buttonStyles['color']));

        return $brizyDonationButton;
    }
}