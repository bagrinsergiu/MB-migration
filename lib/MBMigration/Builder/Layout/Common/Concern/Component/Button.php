<?php

namespace MBMigration\Builder\Layout\Common\Concern\Component;

use Exception;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\Concern\CssPropertyExtractorAware;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;
use MBMigration\Builder\Utils\ColorConverter;
use MBMigration\Builder\Utils\FontUtils;
use MBMigration\Core\Logger;

trait Button
{
    use CssPropertyExtractorAware;

    private static $buttonCache = ['id' => 0, 'button' => null];

    /**
     * Process and add all items the same brizy section
     */
    protected function handleButton(
        ElementContextInterface $data,
        BrowserPageInterface    $browserPage,
        array                   $brizyKit,
                                $selector = null,
                                $options = null,
        array                   $customStyles = []
    ): BrizyComponent
    {

        $mbSection = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        if (!isset($brizyKit['donation-button'])) {
            Logger::instance()->critical('The BrizyKit does not contain the key: donation-button', [$mbSection['typeSection']]);
        }

        try {
            switch ($mbSection['category']) {
                case "button":

                    if (self::$buttonCache['id'] === $options) {
                        $brizySection->getValue()->add_items([self::$buttonCache['button']]);
                        break;
                    }

                    $selector = $selector ?? '[data-id="' . $mbSection['id'] . '"]';

                    $brizyButton = new BrizyComponent(json_decode($brizyKit['donation-button'], true));

                    $brizyButton = $this->setButtonStyles(
                        $brizyButton,
                        $browserPage,
                        $selector,
                        $data,
                        $mbSection
                    );

                    $brizyButton = $this->setHoverButtonStyles(
                        $brizyButton,
                        $browserPage,
                        $selector,
                        $data,
                        $mbSection
                    );

                    $this->setCustomStyles($customStyles, $brizyButton);

                    self::$buttonCache['id'] = $options;
                    self::$buttonCache['button'] = $brizyButton;
                    $brizySection->getValue()->add_items([$brizyButton]);
                    break;
            }
        } catch (Exception $e) {
            Logger::instance()->critical($e->getMessage(), [$mbSection['typeSection']]);
        }

        return $brizySection;
    }

    protected function setCustomStyles(array $customStyles, BrizyComponent $brizyButton)
    {
        foreach ($customStyles as $key => $value) {
            $paramName = 'set_' . $key;
            $brizyButton->getValue()
                ->$paramName($value);
        }
    }

    /**
     * @param BrowserPageInterface $browserPage
     * @param string $selector
     * @param ElementContextInterface $data
     * @param BrizyComponent $brizyButton
     * @param array $mbSection
     * @return BrizyComponent
     * @throws BrowserScriptException
     */
    protected function setButtonStyles(
        BrizyComponent          $brizyButton,
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
                    'padding-top',
                    'padding-bottom',
                    'padding-right',
                    'padding-left',
                ],
                'families' => $data->getFontFamilies(),
                'defaultFamily' => $data->getDefaultFontFamily(),
            ]
        );

        $buttonPaddingStyles = $browserPage->evaluateScript(
            'brizy.getStyles',
            [
                'selector' => $selector . ' div',
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

        $buttonStyles['data']['padding-top'] = (int)$buttonStyles['data']['padding-top'] + (int)$buttonPaddingStyles['data']['padding-top'] ?? 0;
        $buttonStyles['data']['padding-bottom'] = (int)$buttonStyles['data']['padding-bottom'] + (int)$buttonPaddingStyles['data']['padding-bottom'] ?? 0;
        $buttonStyles['data']['padding-right'] = (int)$buttonStyles['data']['padding-right'] + (int)$buttonPaddingStyles['data']['padding-right'] ?? 0;
        $buttonStyles['data']['padding-left'] = (int)$buttonStyles['data']['padding-left'] + (int)$buttonPaddingStyles['data']['padding-left'] ?? 0;

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

        $buttonTextTransform = $buttonTextTransform['data'];

        if (empty($buttonStyles)) {
            throw new BrowserScriptException("The element with selector {$selector} was not found in page.");
        }

        if (isset($buttonStyles['error'])) {
            throw new BrowserScriptException($buttonStyles['error']);
        }

        $buttonStyles = $buttonStyles['data'];

        if (isset($mbSection['settings']['alignment']) && $mbSection['settings']['alignment'] != '') {
            $brizyButton->getValue()->set_horizontalAlign(
                $mbSection['settings']['alignment']
            );
        } else {
            $brizyButton->getValue()->set_horizontalAlign('center');
        }

        switch ($buttonTextTransform['text-transform']) {
            case 'uppercase':
                $brizyButton->getItemValueWithDepth(0)
                    ->set_uppercase(true)
                    ->set_lowercase(false);

                $buttonText = strtoupper($this->stripAnyCode($mbSection['content']));
                break;
            case 'lowercase':
                $brizyButton->getItemValueWithDepth(0)
                    ->set_lowercase(true)
                    ->set_uppercase(false);

                $buttonText = strtolower($this->stripAnyCode($mbSection['content']));
                break;
            default:
                $brizyButton->getItemValueWithDepth(0)
                    ->set_uppercase(false)
                    ->set_lowercase(false);

                $buttonText = strtolower($this->stripAnyCode($mbSection['content']));
                break;
        }
        $buttonItem = $brizyButton->getItemValueWithDepth(0);

        $this->setButtonLinks($buttonItem, $mbSection);

        $buttonItem
            ->set_text($buttonText ?? 'Go here')
            ->set_fillType('filled' ?? 'outline')
            ->set_size('custom')
            ->set_paddingTB((int)$buttonStyles['padding-top'])
            ->set_paddingRL((int)$buttonStyles['padding-right'])
            ->set_paddingRLSuffix("px")
            ->set_paddingTBSuffix("px")
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

        try {
            $fonts = $data->getFontFamilies();
            $convertedFontFamily = FontUtils::transliterateFontFamily($buttonStyles['font-family']);
            $font = $fonts[$convertedFontFamily] ?? $fonts[$data->getDefaultFontFamily()];

            if (!empty($font)) {
                $brizyButton->getItemWithDepth(0)->addFont(
                    (int)$buttonStyles['font-size'],
                    $font['name'],
                    $font['type'],
                    $buttonStyles['font-weight']
                );
            }
        } catch (\Exception $e) {
            Logger::instance()->warning('Error on set font: ' . $e->getMessage() . '');
        }

        return $brizyButton;
    }

    protected function stripAnyCode($content): string
    {
        $content = html_entity_decode($content);
        $content = strip_tags($content);

        $content = preg_replace('/\s+/', ' ', $content);

        return trim($content);
    }

    /**
     * @param BrowserPageInterface $browserPage
     * @param string $selector
     * @param ElementContextInterface $data
     * @param BrizyComponent $brizyButton
     * @param array $mbSection
     * @return BrizyComponent
     * @throws BrowserScriptException
     */
    protected function setHoverButtonStyles(
        BrizyComponent          $brizyButton,
        BrowserPageInterface    $browserPage,
        string                  $selector,
        ElementContextInterface $data,
        array                   $mbSection
    ): BrizyComponent
    {
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
                    'border-color',
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

        if (!empty($mbSection['settings']['sections']['donations']['alignment'])) {
            $brizyButton->getValue()->set_horizontalAlign(
                $mbSection['settings']['alignment']
            );
        } else {
            $brizyButton->getValue()->set_horizontalAlign('center');
        }

        $brizyButton->getItemValueWithDepth(0)
            ->set_hoverBgColorHex(ColorConverter::rgba2hex($buttonStyles['background-color']))
            ->set_hoverBgColorOpacity(ColorConverter::rgba2opacity($buttonStyles['background-color']))
            ->set_hoverBgColorPalette("")
            ->set_hoverBorderColorHex(ColorConverter::rgba2hex($buttonStyles['border-color']))
            ->set_hoverBorderColorOpacity(ColorConverter::rgba2opacity($buttonStyles['border-color']))
            ->set_hoverBorderColorPalette("")
            ->set_hoverColorHex(ColorConverter::rgba2hex($buttonStyles['color']))
            ->set_hoverColorOpacity(ColorConverter::rgba2opacity($buttonStyles['color']))
            ->set_hoverColorPalette("");

        return $brizyButton;
    }

    protected function getButtonStyle(ElementContextInterface $data): array
    {
        $mbSectionId = $data->getMbSection()['sectionId'];

        $buttonSelector = [
            'div.event-calendar-footer .sites-button',
            'button.sites-button',
            'a.sites-button',
        ];

        foreach ($buttonSelector as $selector) {

            $selector = "[data-id='" . $mbSectionId . "'] " . $selector;

            if ($this->hasNode($selector, $this->browserPage)) {
                return $this->searchButton($selector, $data);

            }

        }

        return [
            'normal' => [],
            'hover' => []
        ];
    }

    protected function searchButton($selector, ElementContextInterface $data): array
    {
        $buttonStyles = $this->browserPage->evaluateScript(
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
                ],
                'families' => $data->getFontFamilies(),
                'defaultFamily' => $data->getDefaultFontFamily(),
            ]
        );

        if (isset($buttonStyles['data'])) {
            $buttonStyles = $buttonStyles['data'];
            foreach ($buttonStyles as $key => $value) {
                $buttonStylesConvert[$key] = ColorConverter::convertColorRgbToHex($value);
            }
        }

        $this->browserPage->triggerEvent('hover', $selector);

        $buttonHoverStyles = $this->browserPage->evaluateScript(
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
                    'border-color',
                    'border-top-style',
                    'background-color',
                ],
                'families' => $data->getFontFamilies(),
                'defaultFamily' => $data->getDefaultFontFamily(),
            ]
        );

        if (isset($buttonHoverStyles['data'])) {
            $buttonStylesHover = $buttonHoverStyles['data'];
            foreach ($buttonStylesHover as $key => $value) {
                $buttonHoverStylesConvert[$key] = ColorConverter::convertColorRgbToHex($value);
            }
        }

        $styles['normal'] = $buttonStylesConvert ?? [];
        $styles['hover'] = $buttonHoverStylesConvert ?? [];

        return $styles;
    }

    private function setButtonLinks($brizySectionItem, $mbItem)
    {
        $brizyComponentValue = $brizySectionItem;

        if (!empty($mbItem['link'])) {
            $new_window = 'off';
            if ($mbItem['new_window']) {
                $new_window = 'on';
            }
            $slash = '/';
            $brizyComponentValue->set_linkType('external');
            $brizyComponentValue->set_linkExternalBlank($new_window);

            $linkType = 'string';
            if (filter_var($mbItem['link'], FILTER_VALIDATE_EMAIL)) {
                $linkType = 'mail';
            }
            if (filter_var($mbItem['link'], FILTER_VALIDATE_URL)) {
                $linkType = 'link';
            }
            if ($this->checkPhoneNumber($mbItem['link'])) {
                $linkType = 'phone';
            }

            switch ($linkType) {
                case 'mail':
                    $brizyComponentValue->set_linkExternal('mailto:' . $mbItem['link']);
                    break;
                case 'phone':
                    $brizyComponentValue->set_linkExternal('tel:' . $mbItem['link']);
                    break;
                case 'string':
                case 'link':
                default:
                    $brizyComponentValue->set_linkExternal($mbItem['link']);
                    break;
            }
        }
    }


}
