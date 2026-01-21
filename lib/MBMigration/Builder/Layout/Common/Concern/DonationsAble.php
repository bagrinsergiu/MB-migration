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
            $typeSection = $mbSection['typeSection'] ?? 'unknown';
            Logger::instance()->critical('The BrizyKit does not contain the key: donation-button', [$typeSection]);
            return $brizySection;
        }

        try {
            switch ($mbSection['category']) {
                case "donation":
                    // Use handleButtonStyle() from RichTextAble to get button styles
                    $buttonStyles = $this->handleButtonStyle($mbSection);

                    $selector = '[data-id="' . $mbSection['sectionId'] . '"]';
                    
                    // Try to find button with fallback selectors
                    $buttonSelector = $this->findButtonSelector($selector, $browserPage);
                    
                    if (!$buttonSelector) {
                        Logger::instance()->warning('Donation button not found in section', [
                            'sectionId' => $mbSection['sectionId'] ?? null,
                            'selector' => $selector
                        ]);
                        return $brizySection;
                    }
                    
                    Logger::instance()->info('Donation button found', [
                        'sectionId' => $mbSection['sectionId'] ?? null,
                        'buttonSelector' => $buttonSelector
                    ]);
                    
                    $brizyDonationButton = new BrizyComponent(json_decode($brizyKit['donation-button'], true));

                    // Apply button styles from RichTextAble
                    $brizyDonationButton = $this->applyButtonStylesToDonation(
                        $brizyDonationButton,
                        $buttonStyles,
                        $browserPage,
                        $buttonSelector,
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
     * Map text-align values to only left, center, or right
     * @param string $textAlign
     * @return string Returns 'left', 'center', or 'right'
     */
    private function mapTextAlign(string $textAlign): string
    {
        $textAlign = strtolower(trim($textAlign));
        
        $mapping = [
            'start' => 'left',
            'end' => 'right',
            '-webkit-center' => 'center',
            '-moz-center' => 'center',
            'left' => 'left',
            'right' => 'right',
            'center' => 'center',
            'justify' => 'left', // justify defaults to left
        ];
        
        return $mapping[$textAlign] ?? 'left';
    }

    /**
     * Check if button has border based on styles
     * @param array $styles
     * @return bool
     */
    private function checkButtonHasBorder(array $styles): bool
    {
        // Check border width - if all are 0 or empty, no border
        $topWidth = (int)preg_replace('/[^0-9]/', '', $styles['border-top-width'] ?? '0');
        $rightWidth = (int)preg_replace('/[^0-9]/', '', $styles['border-right-width'] ?? '0');
        $bottomWidth = (int)preg_replace('/[^0-9]/', '', $styles['border-bottom-width'] ?? '0');
        $leftWidth = (int)preg_replace('/[^0-9]/', '', $styles['border-left-width'] ?? '0');
        
        $hasWidth = ($topWidth > 0 || $rightWidth > 0 || $bottomWidth > 0 || $leftWidth > 0);
        
        // Check border style - if all are 'none', no border
        $topStyle = strtolower(trim($styles['border-top-style'] ?? 'none'));
        $rightStyle = strtolower(trim($styles['border-right-style'] ?? 'none'));
        $bottomStyle = strtolower(trim($styles['border-bottom-style'] ?? 'none'));
        $leftStyle = strtolower(trim($styles['border-left-style'] ?? 'none'));
        
        $hasStyle = ($topStyle !== 'none' || $rightStyle !== 'none' || $bottomStyle !== 'none' || $leftStyle !== 'none');
        
        return $hasWidth && $hasStyle;
    }

    /**
     * Get border style from button styles
     * @param array $styles
     * @return string Returns 'solid', 'dashed', 'dotted', or 'none'
     */
    private function getBorderStyle(array $styles): string
    {
        // Check styles in order: top, right, bottom, left
        $stylesToCheck = [
            $styles['border-top-style'] ?? 'none',
            $styles['border-right-style'] ?? 'none',
            $styles['border-bottom-style'] ?? 'none',
            $styles['border-left-style'] ?? 'none',
        ];
        
        foreach ($stylesToCheck as $style) {
            $style = strtolower(trim($style));
            if ($style !== 'none' && !empty($style)) {
                // Map common border styles
                $validStyles = ['solid', 'dashed', 'dotted', 'double', 'groove', 'ridge', 'inset', 'outset'];
                if (in_array($style, $validStyles)) {
                    // Most common styles that are supported
                    if (in_array($style, ['solid', 'dashed', 'dotted'])) {
                        return $style;
                    }
                    // For other styles, default to solid
                    return 'solid';
                }
            }
        }
        
        return 'solid'; // Default fallback
    }

    /**
     * Get border color from button styles
     * @param array $styles
     * @return array Returns ['hex' => string, 'opacity' => float]
     */
    private function getBorderColor(array $styles): array
    {
        // Try to get color from any side (prefer top)
        $colorValues = [
            $styles['border-top-color'] ?? null,
            $styles['border-right-color'] ?? null,
            $styles['border-bottom-color'] ?? null,
            $styles['border-left-color'] ?? null,
        ];
        
        foreach ($colorValues as $color) {
            if (!empty($color) && $color !== 'rgba(0, 0, 0, 0)' && $color !== 'transparent') {
                return [
                    'hex' => ColorConverter::rgba2hex($color),
                    'opacity' => ColorConverter::rgba2opacity($color)
                ];
            }
        }
        
        // Default fallback
        return [
            'hex' => '#000000',
            'opacity' => 1
        ];
    }

    /**
     * Get border width from button styles
     * @param array $styles
     * @return int Returns max border width
     */
    private function getBorderWidth(array $styles): int
    {
        $topWidth = (int)preg_replace('/[^0-9]/', '', $styles['border-top-width'] ?? '0');
        $rightWidth = (int)preg_replace('/[^0-9]/', '', $styles['border-right-width'] ?? '0');
        $bottomWidth = (int)preg_replace('/[^0-9]/', '', $styles['border-bottom-width'] ?? '0');
        $leftWidth = (int)preg_replace('/[^0-9]/', '', $styles['border-left-width'] ?? '0');
        
        return max($topWidth, $rightWidth, $bottomWidth, $leftWidth);
    }

    /**
     * Find button selector with fallback options
     * @param string $sectionSelector
     * @param BrowserPageInterface $browserPage
     * @return string|null
     */
    private function findButtonSelector(string $sectionSelector, BrowserPageInterface $browserPage): ?string
    {
        // Check if RichTextAble trait has hasNode method
        $hasNodeMethod = method_exists($this, 'hasNode');
        
        // List of selectors to try in order
        $selectorsToTry = [
            $sectionSelector . ' button.sites-button', // Primary selector
            $sectionSelector . ' .sites-button', // Alternative: class only
            $sectionSelector . ' button.donation', // Alternative: donation class
            $sectionSelector . ' .donation button', // Alternative: button inside .donation
            $sectionSelector . ' button', // Fallback: any button in section
        ];
        
        foreach ($selectorsToTry as $selector) {
            if ($hasNodeMethod) {
                if ($this->hasNode($selector, $browserPage)) {
                    return $selector;
                }
            } else {
                // Fallback: use browserPage hasNode if available
                if (method_exists($browserPage, 'hasNode')) {
                    if ($browserPage->hasNode($selector)) {
                        return $selector;
                    }
                }
            }
        }
        
        return null;
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
                    'border-top-width',
                    'border-right-width',
                    'border-bottom-width',
                    'border-left-width',
                    'border-top-style',
                    'border-right-style',
                    'border-bottom-style',
                    'border-left-style',
                    'border-top-color',
                    'border-right-color',
                    'border-bottom-color',
                    'border-left-color',
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

        // Find text-align from the button's nearest block parent
        // Try different selectors to find the block parent's text-align
        $buttonAlignment = null;
        
        // Extract section selector from button selector
        $sectionSelector = preg_replace('/\s+button[.\s].*$/', '', $selector);
        
        // First, try getting text-align from common parent containers
        $alignmentSelectors = [
            $sectionSelector . ' .donation', // Parent with .donation class (most common)
            $sectionSelector . ' .text.donation', // Parent with both .text and .donation classes
            $sectionSelector . ' .text', // Parent with .text class
            $selector, // The button itself (fallback)
            $sectionSelector . ' > a', // Link parent of button
        ];
        
        foreach ($alignmentSelectors as $alignmentSelector) {
            try {
                $alignmentResult = $browserPage->evaluateScript(
                    'brizy.getStyles',
                    [
                        'selector' => $alignmentSelector,
                        'styleProperties' => [
                            'text-align'
                        ],
                        'families' => $data->getFontFamilies(),
                        'defaultFamily' => $data->getDefaultFontFamily(),
                    ]
                );
                
                if (!empty($alignmentResult['data']) && !empty($alignmentResult['data']['text-align'])) {
                    $buttonAlignment = $alignmentResult;
                    Logger::instance()->info('Button alignment found', [
                        'selector' => $alignmentSelector,
                        'text-align' => $alignmentResult['data']['text-align']
                    ]);
                    break;
                }
            } catch (Exception $e) {
                // Continue to next selector if this one fails
                Logger::instance()->debug('Failed to get alignment from selector', [
                    'selector' => $alignmentSelector,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        // Fallback: use default alignment structure
        if (!$buttonAlignment) {
            $buttonAlignment = ['data' => []];
            Logger::instance()->info('Button alignment not found, using default', [
                'buttonSelector' => $selector
            ]);
        }

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

        // Set alignment with mapping
        if (isset($buttonAlignment['text-align'])) {
            $alignment = $this->mapTextAlign($buttonAlignment['text-align']);
            $brizyDonationButton->getValue()
                ->set_horizontalAlign($alignment)
                ->set_mobileHorizontalAlign($alignment);
        } else {
            $alignment = $mbSection['settings']['sections']['donations']['alignment'] ?? 'left';
            $alignment = $this->mapTextAlign($alignment);
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

        // Check if button has border and apply border styles
        $hasBorder = $this->checkButtonHasBorder($additionalStyles);
        $borderStyle = 'none';
        $borderColor = ['hex' => '#000000', 'opacity' => 1];
        $borderWidth = 0;
        
        if ($hasBorder) {
            // Button has border - use border style from source
            $borderStyle = $this->getBorderStyle($additionalStyles);
            $borderColor = $this->getBorderColor($additionalStyles);
            $borderWidth = $this->getBorderWidth($additionalStyles);
            
            $buttonItem
                ->set_borderStyle($borderStyle)
                ->set_borderColorHex($borderColor['hex'] ?? '#000000')
                ->set_borderColorPalette('')
                ->set_borderColorOpacity($borderColor['opacity'] ?? 1);
            
            // Set border width if needed
            if ($borderWidth > 0) {
                // Get individual border widths
                $topWidth = (int)preg_replace('/[^0-9]/', '', $additionalStyles['border-top-width'] ?? '0');
                $rightWidth = (int)preg_replace('/[^0-9]/', '', $additionalStyles['border-right-width'] ?? '0');
                $bottomWidth = (int)preg_replace('/[^0-9]/', '', $additionalStyles['border-bottom-width'] ?? '0');
                $leftWidth = (int)preg_replace('/[^0-9]/', '', $additionalStyles['border-left-width'] ?? '0');
                
                if ($topWidth === $rightWidth && $rightWidth === $bottomWidth && $bottomWidth === $leftWidth) {
                    // All sides same - use grouped mode (only set borderWidth, NOT individual widths)
                    $buttonItem
                        ->set_borderWidthType('grouped')
                        ->set_borderWidth($topWidth);
                } else {
                    // Different widths - use ungrouped mode (set individual widths)
                    $buttonItem
                        ->set_borderWidthType('ungrouped')
                        ->set_borderTopWidth($topWidth)
                        ->set_borderRightWidth($rightWidth)
                        ->set_borderBottomWidth($bottomWidth)
                        ->set_borderLeftWidth($leftWidth);
                }
            }
            
            Logger::instance()->info('Button border applied', [
                'borderStyle' => $borderStyle,
                'borderColor' => $borderColor['hex'] ?? null,
                'borderWidth' => $borderWidth
            ]);
        } else {
            // Button has no border - disable border
            $buttonItem->set_borderStyle('none');
            Logger::instance()->info('Button border disabled (no border on source)');
        }
        
        // Apply colors from handleButtonStyle()
        if (!empty($normalStyles)) {
            $buttonItem
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
                ->set_hoverColorOpacity($hoverStyles['color-opacity'] ?? 1)
                ->set_hoverColorHex($hoverStyles['color'] ?? '#ffffff')
                ->set_hoverColorPalette("");
            
            // Apply hover border only if button has border
            if ($hasBorder) {
                $hoverBorderColor = $hoverStyles['border-bottom-color'] ?? $borderColor['hex'] ?? '#000000';
                $hoverBorderOpacity = $hoverStyles['border-bottom-color-opacity'] ?? $borderColor['opacity'] ?? 1;
                
                $buttonItem
                    ->set_hoverBorderColorHex($hoverBorderColor)
                    ->set_hoverBorderColorPalette("")
                    ->set_hoverBorderColorOpacity($hoverBorderOpacity);
            }
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
