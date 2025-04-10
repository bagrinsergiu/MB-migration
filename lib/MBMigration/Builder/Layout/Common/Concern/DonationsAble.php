<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use Exception;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BrizyKitNotFound;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorConverter;

trait DonationsAble
{
    /**
     * Process and add all items the same brizy section
     */
    protected function handleDonations(
        ElementContextInterface $data,
        BrowserPageInterface    $browserPage,
        array                   $brizyKit
    ): BrizyComponent
    {

        $mbSection = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        if (!isset($brizyKit['donation-button'])) {
            throw new BrizyKitNotFound('The BrizyKit does not contain the key: donation-button');
        }

        try {
            switch ($mbSection['category']) {
                case "donation":
                    $selector = '[data-id="' . $mbSection['sectionId'] . '"]';
                    $brizyDonationButton = new BrizyComponent(json_decode($brizyKit['donation-button'], true));
                    $brizyDonationButton = $this->setButtonDonationStyles(
                        $brizyDonationButton,
                        $browserPage,
                        $selector . '  button.sites-button',
                        $data,
                        $mbSection
                    );

                    $brizyDonationButton = $this->setHoveDonationButtonStyles(
                        $brizyDonationButton,
                        $browserPage,
                        $selector . ' button.sites-button',
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
    protected function setButtonDonationStyles(
        BrizyComponent          $brizyDonationButton,
        BrowserPageInterface    $browserPage,
        string                  $selector,
        ElementContextInterface $data,
        array                   $mbSection
    ): BrizyComponent
    {

        $browserPage->triggerEvent('hover', 'html');

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
                    'opacity',
                ],
                'families' => $data->getFontFamilies(),
                'defaultFamily' => $data->getDefaultFontFamily(),
            ]
        );

        $paddingButtonStyles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $selector . ' span',
                'styleProperties' => [
                    'padding-top',
                    'padding-bottom',
                    'padding-right',
                    'padding-left',
                ],
                'families' => $data->getFontFamilies(),
                'defaultFamily' => $data->getDefaultFontFamily(),
            ]
        );
        $paddingButtonStyles = $paddingButtonStyles['data'];


        $buttonTextTransform = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $selector,
                'styleProperties' => [
                    'text-transform'
                ],
                'families' => $data->getFontFamilies(),
                'defaultFamily' => $data->getDefaultFontFamily(),
            ]
        );

        $buttonAlignment = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $selector . ' span',
                'styleProperties' => [
                    'text-align'
                ],
                'families' => $data->getFontFamilies(),
                'defaultFamily' => $data->getDefaultFontFamily(),
            ]
        );

        $browserPage->getPageScreen('_normal_btn');

        $buttonTextTransform = $buttonTextTransform['data'] ?? [];
        $buttonStyles = $buttonStyles['data'] ?? [];
        $buttonAlignment = $buttonAlignment['data'] ?? [];

        if (empty($buttonStyles)) {
            throw new BrowserScriptException("The element with selector {$selector} was not found in page.");
        }

        if (isset($buttonStyles['error'])) {
            throw new BrowserScriptException($buttonStyles['error']);
        }

        if (isset($buttonAlignment['text-align'])) {
            $brizyDonationButton->getValue()
                ->set_horizontalAlign($buttonAlignment['text-align'])
                ->set_mobileHorizontalAlign($buttonAlignment['text-align']);
        } else {
            if (isset($mbSection['settings']['sections']['donations']['alignment']) && $mbSection['settings']['sections']['donations']['alignment'] != '') {
                $brizyDonationButton->getValue()
                    ->set_horizontalAlign($mbSection['settings']['sections']['donations']['alignment'])
                    ->set_mobileHorizontalAlign($mbSection['settings']['sections']['donations']['alignment']);
            } else {
                $brizyDonationButton->getValue()
                    ->set_horizontalAlign('left')
                    ->set_mobileHorizontalAlign('left');
            }
        }

        switch ($buttonTextTransform['text-transform']) {
            case 'uppercase':
                $brizyDonationButton->getItemValueWithDepth(0)
                    ->set_uppercase(true)
                    ->set_lowercase(false);

                $buttonText = strtoupper($mbSection['settings']['sections']['donations']['text']);
                break;
            case 'lowercase':
                $brizyDonationButton->getItemValueWithDepth(0)
                    ->set_lowercase(true)
                    ->set_uppercase(false);

                $buttonText = strtolower($mbSection['settings']['sections']['donations']['text']);
                break;
            default:
                $brizyDonationButton->getItemValueWithDepth(0)
                    ->set_uppercase(false)
                    ->set_lowercase(false);
                break;
        }

        if (empty($buttonText)) {
            $buttonText = 'MAKE A DONATION';
        }

        if ((int)$paddingButtonStyles['padding-top'] == 0) {
            if ((int)$buttonStyles['padding-top'] !== 0) {
                $b_paddingTB = (int)$buttonStyles['padding-top'];
            } else {
                $b_paddingTB = 10;
            }
        } else {
            $b_paddingTB = (int)$paddingButtonStyles['padding-top'];
        }

        if ((int)$paddingButtonStyles['padding-right'] == 0) {
            if ((int)$buttonStyles['padding-right'] !== 0) {
                $b_paddingRL = (int)$buttonStyles['padding-right'];
            } else {
                $b_paddingRL = 10;
            }
        } else {
            $b_paddingRL = (int)$paddingButtonStyles['padding-right'];
        }

        $brizyDonationButton->getItemValueWithDepth(0)
            ->set_text($buttonText ?? 'MAKE A DONATION')
            ->set_linkExternal($mbSection['settings']['sections']['donations']['url'] ?? '#')
            ->set_linkExternalBlank($this->detectLinkExternalBlank($mbSection, $browserPage))
            ->set_size('custom')
            ->set_paddingType('ungrouped')
            ->set_paddingTB($b_paddingTB)
            ->set_paddingTBSuffix('px')
            ->set_paddingTop((int)$paddingButtonStyles['padding-top'])
            ->set_paddingBottom((int)$paddingButtonStyles['padding-bottom'])
            ->set_paddingRight((int)$paddingButtonStyles['padding-right'])
            ->set_paddingRL($b_paddingRL)
            ->set_paddingRLSuffix('px')
            ->set_paddingLeft((int)$paddingButtonStyles['padding-left'])
            ->set_marginType('ungrouped')
            ->set_marginLeft((int)$buttonStyles['margin-left'])
            ->set_marginRight((int)$buttonStyles['margin-right'])
            ->set_marginTop((int)$buttonStyles['margin-top'])
            ->set_marginBottom((int)$buttonStyles['margin-bottom'])
            ->set_borderRadiusType('custom')
            ->set_borderRadius((int)$buttonStyles['border-bottom-left-radius'])
            ->set_borderStyle($buttonStyles['border-top-style'])
            ->set_borderColorHex(ColorConverter::rgba2hex($buttonStyles['border-top-color']))
            ->set_borderColorPalette('')
            ->set_borderColorOpacity(ColorConverter::rgba2opacity($buttonStyles['border-top-color']))
            ->set_bgColorOpacity(ColorConverter::normalizeOpacity($buttonStyles['opacity']))
            ->set_bgColorHex(ColorConverter::rgba2hex($buttonStyles['background-color']))
            ->set_bgColorPalette("")
            ->set_colorHex(ColorConverter::rgba2hex($buttonStyles['color']))
            ->set_colorOpacity(ColorConverter::rgba2opacity($buttonStyles['color']))
            ->set_colorPalette("");

        return $brizyDonationButton;
    }

    protected function detectLinkExternalBlank(array $mbSection, BrowserPageInterface $browserPage): string
    {
        $selector = '[data-id="' . $mbSection['sectionId'] . '"] .donation > a';

        $buttonTarget = $browserPage->evaluateScript(
            'brizy.getAttributes',
            [   //#donation-3697334 > div.content-wrapper.clearfix > div.text.donation.center > a
                'selector' => $selector,
                'attributeNames' => [
                    'target',
                ],
            ]
        );

        if (isset($buttonTarget['error'])) {
            return 'off';
        }

        $buttonTarget = $buttonTarget['data'];

        if ($buttonTarget['target'] == '_blank') {

            return 'on';
        } else {

            return 'off';
        }
    }

    protected function setHoveDonationButtonStyles(
        BrizyComponent          $brizyDonationButton,
        BrowserPageInterface    $browserPage,
        string                  $selector,
        ElementContextInterface $data,
        array                   $mbSection
    ): BrizyComponent
    {
        $browserPage->triggerEvent('hover', $selector);
        usleep(500);
        $browserPage->getPageScreen('_btn');
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
                    'opacity',
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

        $brizyDonationButton->getItemValueWithDepth(0)
            ->set_fillType('filled')
            ->set_hoverBgColorHex(ColorConverter::rgba2hex($buttonStyles['background-color']))
            ->set_hoverBgColorPalette("")
            ->set_hoverBgColorOpacity(0.75 ?? ColorConverter::rgba2opacity($buttonStyles['background-color']))
            ->set_hoverBorderColorHex(ColorConverter::rgba2hex($buttonStyles['border-top-color']))
            ->set_hoverBorderColorPalette("")
            ->set_hoverBorderColorOpacity(ColorConverter::rgba2opacity($buttonStyles['color']))
            ->set_hoverBorderColorHex(ColorConverter::rgba2hex($buttonStyles['color']))
            ->set_hoverBorderColorPalette("")
            ->set_hoverColorOpacity(ColorConverter::rgba2opacity($buttonStyles['color']))
            ->set_hoverColorHex(ColorConverter::rgba2hex($buttonStyles['color']))
            ->set_hoverColorPalette("");

        return $brizyDonationButton;
    }
}
