<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use Exception;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\FontUtils;
use MBMigration\Core\Logger;

trait DonationsAble
{
    /**
     * Process and add all items the same brizy section
     * @throws Exception
     */
    protected function handleDonationsButton(
        ElementContextInterface $data,
        BrowserPageInterface    $browserPage,
        array                   $brizyKit,
        array                   $options = null,
        string                  $textButtonTransform = 'none'
    ): BrizyComponent
    {

        $mbSection = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        if (!isset($brizyKit['donation-button'])) {
            Logger::instance()->critical('The BrizyKit does not contain the key: donation-button', [$mbSection['typeSection']]);
            return $brizySection;
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
                        $mbSection,
                        $textButtonTransform
                    );

                    $brizyDonationButton = $this->setHoveDonationButtonStyles(
                        $brizyDonationButton,
                        $browserPage,
                        $selector . ' button.sites-button',
                        $data,
                        $mbSection
                    );

                    if ($options && $this->hasMobilePaddingOptions($options)) {
                        $brizyDonationButton->addMobilePadding([
                            $options['mobilePaddingTop'] ?? 0,
                            $options['mobilePaddingRight'] ?? 0,
                            $options['mobilePaddingBottom'] ?? 0,
                            $options['mobilePaddingLeft'] ?? 0,
                        ]);
                    } else {
                        $brizyDonationButton->addMobilePadding([10, 0, 10, 0,]);
                    }

                    $brizySection->getValue()->add_items([$brizyDonationButton]);
                    break;
            }
        } catch (Exception $e) {
            Logger::instance()->error('The Donate Button element returns an error message', [$e->getMessage(), $e->getTraceAsString(), $mbSection['typeSection']]);
            return $brizySection;
        }

        return $brizySection;
    }

    private function hasMobilePaddingOptions(array $options): bool
    {
        $paddingKeys = [
            'mobilePaddingTop',
            'mobilePaddingRight',
            'mobilePaddingBottom',
            'mobilePaddingLeft',
        ];

        return (bool)array_intersect($paddingKeys, array_keys($options));
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
        array                   $mbSection,
        string                  $textButtonTransform = 'none'
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
                    'padding-top',
                    'padding-bottom',
                    'padding-right',
                    'padding-left',
                ],
                'families' => $data->getFontFamilies(),
                'defaultFamily' => $data->getDefaultFontFamily(),
            ]
        );

        $paddingButtonStyles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $selector . ' >*',
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

        $paddingButtonStyles['data']['padding-top'] = (int)$buttonStyles['data']['padding-top'] + (int)$paddingButtonStyles['data']['padding-top'] ?? 0;
        $paddingButtonStyles['data']['padding-bottom'] = (int)$buttonStyles['data']['padding-bottom'] + (int)$paddingButtonStyles['data']['padding-bottom'] ?? 0;
        $paddingButtonStyles['data']['padding-right'] = (int)$buttonStyles['data']['padding-right'] + (int)$paddingButtonStyles['data']['padding-right'] ?? 0;
        $paddingButtonStyles['data']['padding-left'] = (int)$buttonStyles['data']['padding-left'] + (int)$paddingButtonStyles['data']['padding-left'] ?? 0;

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

        if($buttonTextTransform['text-transform'] === 'none'){
            $buttonTextTransform['text-transform'] = $textButtonTransform;
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

                $buttonText = strtolower($mbSection['settings']['sections']['donations']['text']);
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
            ->set_bgColorOpacity(ColorConverter::rgba2opacity($buttonStyles['background-color']))
            ->set_bgColorHex(ColorConverter::rgba2hex($buttonStyles['background-color']))
            ->set_bgColorPalette("")
            ->set_colorHex(ColorConverter::rgba2hex($buttonStyles['color']))
            ->set_colorOpacity(ColorConverter::rgba2opacity($buttonStyles['color']))
            ->set_colorPalette("");


        $fontList = $data->getFontFamilies();

        if ($font = $fontList[FontUtils::transliterateFontFamily($buttonStyles['font-family'])]) {
            $butonFont = [
                'fontFamily' => $font['name'],
                'fontFamilyType' => $font['type'],
            ];
        } else {
            $butonFont = [
                'fontFamily' => "lato",
                'fontFamilyType' => "google",
            ];
        }

        $brizyDonationButton->getItemWithDepth(0)->addFont(
            (int)$buttonStyles['font-size'],
            $butonFont['fontFamily'],
            $butonFont['fontFamilyType'],
        );

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
            ->set_hoverBorderStyle($buttonStyles['border-top-style'] ?? 'none')
            ->set_hoverBgColorHex(ColorConverter::rgba2hex($buttonStyles['background-color']))
            ->set_hoverBgColorPalette("")
            ->set_hoverBgColorOpacity( ColorConverter::rgba2opacity($buttonStyles['background-color']) ?? 0.75)

            ->set_hoverBorderColorHex(ColorConverter::rgba2hex($buttonStyles['border-top-color']))
            ->set_hoverBorderColorPalette("")
            ->set_hoverBorderColorOpacity(ColorConverter::rgba2opacity($buttonStyles['border-top-color']))

            ->set_hoverColorOpacity(ColorConverter::rgba2opacity($buttonStyles['color']))
            ->set_hoverColorHex(ColorConverter::rgba2hex($buttonStyles['color']))
            ->set_hoverColorPalette("");

        return $brizyDonationButton;
    }
}
