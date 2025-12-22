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
    use RichTextAble;
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
                    // Use handleButtonStyle() from RichTextAble to get button styles
                    $buttonStyles = $this->handleButtonStyle($mbSection);

                    $selector = '[data-id="' . $mbSection['sectionId'] . '"]';
                    $brizyDonationButton = new BrizyComponent(json_decode($brizyKit['donation-button'], true));

                    // Apply button styles from RichTextAble
                    $brizyDonationButton = $this->applyButtonStylesToDonation(
                        $brizyDonationButton,
                        $buttonStyles,
                        $browserPage,
                        $selector . ' button.sites-button',
                        $data,
                        $mbSection,
                        $textButtonTransform
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
     * Apply button styles from RichTextAble to donation button
     * @param BrizyComponent $brizyDonationButton
     * @param array $buttonStyles Styles from handleButtonStyle() ['normal' => [...], 'hover' => [...]]
     * @param BrowserPageInterface $browserPage
     * @param string $selector
     * @param ElementContextInterface $data
     * @param array $mbSection
     * @param string $textButtonTransform
     * @return BrizyComponent
     * @throws BrowserScriptException
     */
    protected function applyButtonStylesToDonation(
        BrizyComponent          $brizyDonationButton,
        array                   $buttonStyles,
        BrowserPageInterface    $browserPage,
        string                  $selector,
        ElementContextInterface $data,
        array                   $mbSection,
        string                  $textButtonTransform = 'none'
    ): BrizyComponent
    {
        $browserPage->triggerEvent('hover', 'html');

        // Extract additional styles not covered by handleButtonStyle()
        $additionalStyles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $selector,
                'styleProperties' => [
                    'font-family',
                    'font-size',
                    'font-weight',
                    'padding-top',
                    'padding-bottom',
                    'padding-right',
                    'padding-left',
                    'margin-top',
                    'margin-bottom',
                    'margin-left',
                    'margin-right',
                    'border-bottom-left-radius',
                    'text-transform',
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

        $additionalStyles = $additionalStyles['data'] ?? [];
        $paddingButtonStyles = $paddingButtonStyles['data'] ?? [];
        $buttonAlignment = $buttonAlignment['data'] ?? [];

        if (empty($additionalStyles)) {
            throw new BrowserScriptException("The element with selector {$selector} was not found in page.");
        }

        // Calculate paddings
        $paddingTop = (int)($additionalStyles['padding-top'] ?? 0) + (int)($paddingButtonStyles['padding-top'] ?? 0);
        $paddingBottom = (int)($additionalStyles['padding-bottom'] ?? 0) + (int)($paddingButtonStyles['padding-bottom'] ?? 0);
        $paddingRight = (int)($additionalStyles['padding-right'] ?? 0) + (int)($paddingButtonStyles['padding-right'] ?? 0);
        $paddingLeft = (int)($additionalStyles['padding-left'] ?? 0) + (int)($paddingButtonStyles['padding-left'] ?? 0);

        // Set alignment
        if (isset($buttonAlignment['text-align'])) {
            $brizyDonationButton->getValue()
                ->set_horizontalAlign($buttonAlignment['text-align'])
                ->set_mobileHorizontalAlign($buttonAlignment['text-align']);
        } else {
            $alignment = $mbSection['settings']['sections']['donations']['alignment'] ?? 'left';
            $brizyDonationButton->getValue()
                ->set_horizontalAlign($alignment)
                ->set_mobileHorizontalAlign($alignment);
        }

        // Handle text transform
        $textTransform = $additionalStyles['text-transform'] ?? 'none';
        if ($textTransform === 'none') {
            $textTransform = $textButtonTransform;
        }

        $buttonText = $mbSection['settings']['sections']['donations']['text'] ?? 'MAKE A DONATION';
        switch ($textTransform) {
            case 'uppercase':
                $brizyDonationButton->getItemValueWithDepth(0)
                    ->set_uppercase(true)
                    ->set_lowercase(false);
                $buttonText = strtoupper($buttonText);
                break;
            case 'lowercase':
                $brizyDonationButton->getItemValueWithDepth(0)
                    ->set_lowercase(true)
                    ->set_uppercase(false);
                $buttonText = strtolower($buttonText);
                break;
            default:
                $brizyDonationButton->getItemValueWithDepth(0)
                    ->set_uppercase(false)
                    ->set_lowercase(false);
                break;
        }

        // Apply styles from handleButtonStyle() (normal state)
        $normalStyles = $buttonStyles['normal'] ?? [];
        $hoverStyles = $buttonStyles['hover'] ?? [];

        $b_paddingTB = $paddingTop > 0 ? $paddingTop : 10;
        $b_paddingRL = $paddingRight > 0 ? $paddingRight : 10;

        $buttonItem = $brizyDonationButton->getItemValueWithDepth(0);
        $buttonItem
            ->set_text($buttonText)
            ->set_linkExternal($mbSection['settings']['sections']['donations']['url'] ?? '#')
            ->set_linkExternalBlank($this->detectLinkExternalBlank($mbSection, $browserPage))
            ->set_size('custom')
            ->set_paddingType('ungrouped')
            ->set_paddingTB($b_paddingTB)
            ->set_paddingTBSuffix('px')
            ->set_paddingTop($paddingTop)
            ->set_paddingBottom($paddingBottom)
            ->set_paddingRight($paddingRight)
            ->set_paddingRL($b_paddingRL)
            ->set_paddingRLSuffix('px')
            ->set_paddingLeft($paddingLeft)
            ->set_marginType('ungrouped')
            ->set_marginLeft((int)($additionalStyles['margin-left'] ?? 0))
            ->set_marginRight((int)($additionalStyles['margin-right'] ?? 0))
            ->set_marginTop((int)($additionalStyles['margin-top'] ?? 0))
            ->set_marginBottom((int)($additionalStyles['margin-bottom'] ?? 0))
            ->set_borderRadiusType('custom')
            ->set_borderRadius((int)($additionalStyles['border-bottom-left-radius'] ?? 0));

        // Apply colors and borders from handleButtonStyle()
        if (!empty($normalStyles)) {
            $buttonItem
                ->set_borderStyle($normalStyles['border-bottom-style'] ?? 'solid')
                ->set_borderColorHex($normalStyles['border-bottom-color'] ?? '#000000')
                ->set_borderColorPalette('')
                ->set_borderColorOpacity($normalStyles['border-bottom-color-opacity'] ?? 1)
                ->set_bgColorOpacity($normalStyles['background-color-opacity'] ?? 1)
                ->set_bgColorHex($normalStyles['background-color'] ?? '#000000')
                ->set_bgColorPalette("")
                ->set_colorHex($normalStyles['color'] ?? '#ffffff')
                ->set_colorOpacity($normalStyles['color-opacity'] ?? 1)
                ->set_colorPalette("");
        }

        // Apply hover styles from handleButtonStyle()
        if (!empty($hoverStyles)) {
            $buttonItem
                ->set_fillType('filled')
                ->set_hoverBgColorHex($hoverStyles['background-color'] ?? '#000000')
                ->set_hoverBgColorPalette("")
                ->set_hoverBgColorOpacity($hoverStyles['background-color-opacity'] ?? 0.75)
                ->set_hoverBorderColorHex($hoverStyles['border-bottom-color'] ?? '#000000')
                ->set_hoverBorderColorPalette("")
                ->set_hoverBorderColorOpacity($hoverStyles['border-bottom-color-opacity'] ?? 1)
                ->set_hoverColorOpacity($hoverStyles['color-opacity'] ?? 1)
                ->set_hoverColorHex($hoverStyles['color'] ?? '#ffffff')
                ->set_hoverColorPalette("");
        }

        // Apply font
        try {
            $fonts = $data->getFontFamilies();
            $convertedFontFamily = FontUtils::transliterateFontFamily($additionalStyles['font-family'] ?? '');
            $font = $fonts[$convertedFontFamily] ?? $fonts[$data->getDefaultFontFamily()];

            if (!empty($font)) {
                $brizyDonationButton->getItemWithDepth(0)->addFont(
                    (int)($additionalStyles['font-size'] ?? 14),
                    $font['name'],
                    $font['type'],
                    $additionalStyles['font-weight'] ?? '400'
                );
            }
        } catch (\Exception $e) {
            Logger::instance()->warning('Error on set font for donation button: ' . $e->getMessage());
        }

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

}
