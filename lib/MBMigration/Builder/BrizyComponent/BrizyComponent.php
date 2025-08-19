<?php

namespace MBMigration\Builder\BrizyComponent;

use JsonSerializable;
use Exception;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Core\Logger;

class BrizyComponent implements JsonSerializable
{
    protected $type;
    protected $value;
    protected $blockId;
    protected ?BrizyComponent $parent = null;

    public function __construct($data, ?BrizyComponent $parent = null)
    {
        if (!is_array($data)) {
            throw new BadJsonProvided('Wrong data format provided for BrizyComponent');
        }

        $this->type = $data['type'] ?? '';
        $this->value = new BrizyComponentValue($data['value'] ?? [], $this);
        $this->parent = $parent;

        if (isset($data['blockId'])) {
            $this->blockId = $data['blockId'];
        }
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     * @return BrizyComponent
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): BrizyComponentValue
    {
        return $this->value;
    }

    public function setValue(BrizyComponentValue $value): BrizyComponent
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getBlockId()
    {
        return $this->blockId;
    }

    /**
     * @param mixed $blockId
     * @return BrizyComponent
     */
    public function setBlockId($blockId)
    {
        $this->blockId = $blockId;

        return $this;
    }

    public function jsonSerialize()
    {
        $getObjectVars = get_object_vars($this);
        unset($getObjectVars['parent']);

        return $getObjectVars;
    }

    public function getItemWithDepth(): ?BrizyComponent
    {
        $depths = func_get_args();

        if (is_array($depths[0])) {
            $depths = $depths[0];
        }

        $item = null;

        foreach ($depths as $index) {
            if ($item) {
                $items = $item->getValue()->get_items();
            } else {
                $items = $this->getValue()->get_items();
            }

            if (!isset($items[$index])) {
                return $item;
            }

            $item = $items[$index];
        }

        return $item;
    }

    /**
     * @return mixed|null
     */
    public function getParent(): ?BrizyComponent
    {
        return $this->parent;
    }

    public function getItemValueWithDepth(): ?BrizyComponentValue
    {
        $depths = func_get_args();

        return call_user_func_array([$this, 'getItemWithDepth'], $depths)->getValue();
    }

    public function addRadius($radiusPx = 0): BrizyComponent
    {
        $radius = array(
            "borderRadiusType" => "grouped",
            "borderRadius" => $radiusPx,
            "borderRadiusSuffix" => "px",
            "borderTopLeftRadius" => $radiusPx,
            "borderTopLeftRadiusSuffix" => "px",
            "borderTopRightRadius" => $radiusPx,
            "borderTopRightRadiusSuffix" => "px",
            "borderBottomRightRadius" => $radiusPx,
            "borderBottomRightRadiusSuffix" => "px",
            "borderBottomLeftRadius" => $radiusPx,
            "borderBottomLeftRadiusSuffix" => "px",
        );

        foreach ($radius as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addPadding($t, $r, $b, $l, $prefix = '', $measureType = 'px'): BrizyComponent
    {
        $this->addConstructPadding($t, 'top', $prefix, $measureType);
        $this->addConstructPadding($r, 'right', $prefix, $measureType);
        $this->addConstructPadding($b, 'bottom', $prefix, $measureType);
        $this->addConstructPadding($l, 'left', $prefix, $measureType);

        return $this;
    }

    public function addGroupedPadding($p = 0, $prefix = '', $measureType = 'px'): BrizyComponent
    {
        $this->addConstructPadding($p, 'top', $prefix, $measureType);
        $this->addConstructPadding($p, 'right', $prefix, $measureType);
        $this->addConstructPadding($p, 'bottom', $prefix, $measureType);
        $this->addConstructPadding($p, 'left', $prefix, $measureType);

        return $this;
    }

    public function addGroupedMargin($p = 0, $prefix = '', $measureType = 'px'): BrizyComponent
    {
        $this->addConstructMargin($p, 'top', $prefix, $measureType);
        $this->addConstructMargin($p, 'right', $prefix, $measureType);
        $this->addConstructMargin($p, 'bottom', $prefix, $measureType);
        $this->addConstructMargin($p, 'left', $prefix, $measureType);

        return $this;
    }

    public function addMargin($t, $r, $b, $l, $prefix = '', $measureType = 'px'): BrizyComponent
    {
        $this->addConstructMargin($t, 'top', $prefix, $measureType);
        $this->addConstructMargin($r, 'right', $prefix, $measureType);
        $this->addConstructMargin($b, 'bottom', $prefix, $measureType);
        $this->addConstructMargin($l, 'left', $prefix, $measureType);

        return $this;
    }

    public function addMobilePadding($paddingPx = 0): BrizyComponent
    {
        if (is_array($paddingPx)) {
            $mobilePadding = [
                "mobilePaddingType" => "ungrouped",
                "mobilePadding" => 0,
                "mobilePaddingSuffix" => "px",
                "mobilePaddingTop" => $paddingPx[0] ?? 0,
                "mobilePaddingTopSuffix" => "px",
                "mobilePaddingRight" => $paddingPx[1] ?? 0,
                "mobilePaddingRightSuffix" => "px",
                "mobilePaddingBottom" => $paddingPx[2] ?? 0,
                "mobilePaddingBottomSuffix" => "px",
                "mobilePaddingLeft" => $paddingPx[3] ?? 0,
                "mobilePaddingLeftSuffix" => "px",
            ];
        } else {
            $mobilePadding = [
                "mobilePaddingType" => "ungrouped",
                "mobilePadding" => $paddingPx,
                "mobilePaddingSuffix" => "px",
                "mobilePaddingTop" => $paddingPx,
                "mobilePaddingTopSuffix" => "px",
                "mobilePaddingRight" => $paddingPx,
                "mobilePaddingRightSuffix" => "px",
                "mobilePaddingBottom" => $paddingPx,
                "mobilePaddingBottomSuffix" => "px",
                "mobilePaddingLeft" => $paddingPx,
                "mobilePaddingLeftSuffix" => "px",
            ];
        }

        foreach ($mobilePadding as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addMobileMargin($marginPx = 0): BrizyComponent
    {
        if (is_array($marginPx)) {
            $mobileMargin = [
                "mobileMarginType" => "ungrouped",
                "mobileMargin" => 0,
                "mobileMarginSuffix" => "px",
                "mobileMarginTop" => $marginPx[0] ?? 0,
                "mobileMarginTopSuffix" => "px",
                "mobileMarginRight" => $marginPx[1] ?? 0,
                "mobileMarginRightSuffix" => "px",
                "mobileMarginBottom" => $marginPx[2] ?? 0,
                "mobileMarginBottomSuffix" => "px",
                "mobileMarginLeft" => $marginPx[3] ?? 0,
                "mobileMarginLeftSuffix" => "px",
            ];

        } else {
            $mobileMargin = [
                "mobileMarginType" => "ungrouped",
                "mobileMargin" => $marginPx,
                "mobileMarginSuffix" => "px",
                "mobileMarginTop" => $marginPx,
                "mobileMarginTopSuffix" => "px",
                "mobileMarginRight" => $marginPx,
                "mobileMarginRightSuffix" => "px",
                "mobileMarginBottom" => $marginPx,
                "mobileMarginBottomSuffix" => "px",
                "mobileMarginLeft" => $marginPx,
                "mobileMarginLeftSuffix" => "px",
            ];
        }

        foreach ($mobileMargin as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addTabletPadding($paddingPx = 0): BrizyComponent
    {
        if (is_array($paddingPx)) {
            $tabletPadding = [
                "tabletPaddingType" => "ungrouped",
                "tabletPadding" => 0,
                "tabletPaddingSuffix" => "px",
                "tabletPaddingTop" => $paddingPx[0] ?? 0,
                "tabletPaddingTopSuffix" => "px",
                "tabletPaddingRight" => $paddingPx[1] ?? 0,
                "tabletPaddingRightSuffix" => "px",
                "tabletPaddingBottom" => $paddingPx[2] ?? 0,
                "tabletPaddingBottomSuffix" => "px",
                "tabletPaddingLeft" => $paddingPx[3] ?? 0,
                "tabletPaddingLeftSuffix" => "px",
            ];
        } else {
            $tabletPadding = [
                "tabletPaddingType" => "ungrouped",
                "tabletPadding" => $paddingPx,
                "tabletPaddingSuffix" => "px",
                "tabletPaddingTop" => $paddingPx,
                "tabletPaddingTopSuffix" => "px",
                "tabletPaddingRight" => $paddingPx,
                "tabletPaddingRightSuffix" => "px",
                "tabletPaddingBottom" => $paddingPx,
                "tabletPaddingBottomSuffix" => "px",
                "tabletPaddingLeft" => $paddingPx,
                "tabletPaddingLeftSuffix" => "px",
            ];
        }

        foreach ($tabletPadding as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addTabletMargin($marginPx = 0): BrizyComponent
    {
        if (is_array($marginPx)) {
            $tabletMargin = [
                "tabletMarginType" => "ungrouped",
                "tabletMargin" => 0,
                "tabletMarginSuffix" => "px",
                "tabletMarginTop" => $marginPx[0] ?? 0,
                "tabletMarginTopSuffix" => "px",
                "tabletMarginRight" => $marginPx[1] ?? 0,
                "tabletMarginRightSuffix" => "px",
                "tabletMarginBottom" => $marginPx[2] ?? 0,
                "tabletMarginBottomSuffix" => "px",
                "tabletMarginLeft" => $marginPx[3] ?? 0,
                "tabletMarginLeftSuffix" => "px",
            ];

        } else {
            $tabletMargin = [
                "tabletMarginType" => "ungrouped",
                "tabletMargin" => $marginPx,
                "tabletMarginSuffix" => "px",
                "tabletMarginTop" => $marginPx,
                "tabletMarginTopSuffix" => "px",
                "tabletMarginRight" => $marginPx,
                "tabletMarginRightSuffix" => "px",
                "tabletMarginBottom" => $marginPx,
                "tabletMarginBottomSuffix" => "px",
                "tabletMarginLeft" => $marginPx,
                "tabletMarginLeftSuffix" => "px",
            ];
        }

        foreach ($tabletMargin as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addPaddingRight_n($padding = 0, $measureType = 'px', $prefix = ''): BrizyComponent
    {
        $formattedPrefix = $prefix !== '' ? strtolower($prefix) : '';

        $paddingLeft = [
            ($formattedPrefix . ($formattedPrefix ? 'PaddingType' : 'paddingType')) => "ungrouped",
            ($formattedPrefix . ($formattedPrefix ? 'PaddingLeft' : 'paddingLeft')) => $padding,
            ($formattedPrefix . ($formattedPrefix ? 'PaddingLeftSuffix' : 'paddingLeftSuffix')) => $measureType,
        ];

        foreach ($paddingLeft as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addPaddingRight($padding = 0, $measureType = 'px'): BrizyComponent
    {
        $padingLeft = [
            "paddingType" => "ungrouped",
            "paddingRight" => $padding,
            "paddingRightSuffix" => $measureType,
        ];

        foreach ($padingLeft as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addPaddingLeft($padding = 0, $measureType = 'px'): BrizyComponent
    {
        $padingLeft = [
            "paddingType" => "ungrouped",
            "paddingLeft" => $padding,
            "paddingLeftSuffix" => $measureType,
        ];

        foreach ($padingLeft as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addMarginBottom($margin = 0, $measureType = 'px'): BrizyComponent
    {
        $marginBottom = [
            "marginType" => "ungrouped",
            "marginBottom" => $margin,
            "marginBottomSuffix" => $measureType,
        ];

        foreach ($marginBottom as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addVerticalContentAlign($verticalAlign = 'top'): BrizyComponent
    {
        if (!in_array($verticalAlign, ['center', 'bottom', 'top'])) {
            $verticalAlign = 'center';
        }

        $this->getValue()->set('verticalAlign', $verticalAlign);
        $this->getValue()->set('mobileVerticalAlign', $verticalAlign);
        $this->getValue()->set('tabletVerticalAlign', $verticalAlign);

        return $this;
    }

    public function addHorizontalContentAlign($verticalAlign = 'center'): BrizyComponent
    {
        if (!in_array($verticalAlign, ['center', 'left', 'right'])) {
            $verticalAlign = 'center';
        }

        $this->getValue()->set('horizontalAlign', $verticalAlign);
        $this->getValue()->set('mobileHorizontalAlign', $verticalAlign);
        $this->getValue()->set('tabletHorizontalAlign', $verticalAlign);

        return $this;
    }

    public function addMobileContentAlign($verticalAlign = 'center'): BrizyComponent
    {
        if (!in_array($verticalAlign, ['center', 'left', 'right'])) {
            $verticalAlign = 'center';
        }

        $this->getValue()->set('mobileHorizontalAlign', $verticalAlign);

        return $this;
    }

    public function addMobileHorizontalContentAlign($verticalAlign = 'center'): BrizyComponent
    {
        if (!in_array($verticalAlign, ['center', 'left', 'right'])) {
            $verticalAlign = 'center';
        }

        $this->getValue()->set('mobileHorizontalAlign', $verticalAlign);

        return $this;
    }

    public function addBgColor($hex, $opacity): BrizyComponent
    {
        $bgColor = [
            "bgColorType" => "solid",
            "bgColorHex" => $hex,
            "bgColorOpacity" => $opacity,
            "bgColorPalette" => '',
        ];

        foreach ($bgColor as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function titleTypography(): BrizyComponent
    {
        $bgColor = [
            "titleTypographyFontStyle" => "",
            "titleTypographyFontFamily" => "lato",
            "titleTypographyFontFamilyType" => "google",
            "titleTypographyFontSize" => 28,
            "titleTypographyFontSizeSuffix" => "px",
            "titleTypographyFontWeight" => 700,
            "titleTypographyLetterSpacing" => -1.5,
            "titleTypographyLineHeight" => 1.4,
            "titleTypographyVariableFontWeight" => 400,
            "titleTypographyFontWidth" => 100,
            "titleTypographyFontSoftness" => 0,
            "titleTypographyBold" => false,
            "titleTypographyItalic" => false,
            "titleTypographyUnderline" => false,
            "titleTypographyStrike" => false,
            "titleTypographyUppercase" => false,
            "titleTypographyLowercase" => false
        ];

        foreach ($bgColor as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addFont($fontSize, $fontFamily, $fontFamilyType, $fontWeight = 700, $lineHeight = 1.6): BrizyComponent
    {
        $bgColor = [
            "fontStyle" => "",
            "fontFamily" => $fontFamily,
            "fontFamilyType" => $fontFamilyType,
            "fontSize" => $fontSize,
            "fontSizeSuffix" => "px",
            "fontWeight" => $fontWeight,
            "letterSpacing" => 0,
            "lineHeight" => $lineHeight,
            "variableFontWeight" => 400,
            "fontWidth" => 100,
            "fontSoftness" => 0,
            "bold" => false,
            "italic" => false,
            "underline" => false,
            "strike" => false
        ];

        foreach ($bgColor as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function previewTypography($typography = []): BrizyComponent
    {
        $bgColor = [
            "previewTypographyFontStyle" => "",
            "previewTypographyFontFamily" => "lato",
            "previewTypographyFontFamilyType" => "google",
            "previewTypographyFontSize" => 16,
            "previewTypographyFontSizeSuffix" => "px",
            "previewTypographyFontWeight" => 400,
            "previewTypographyLetterSpacing" => 0,
            "previewTypographyLineHeight" => $typography['lineHeight'] ?? 1.9,
            "previewTypographyVariableFontWeight" => 400,
            "previewTypographyFontWidth" => 100,
            "previewTypographyFontSoftness" => 0,
            "previewTypographyBold" => false,
            "previewTypographyItalic" => false,
            "previewTypographyUnderline" => false,
            "previewTypographyStrike" => false,
            "previewTypographyUppercase" => false,
            "previewTypographyLowercase" => false
        ];

        foreach ($bgColor as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function typography($typography = []): BrizyComponent
    {
        $bgColor = [
            "typographyFontStyle" => "",
            "typographyFontFamily" => "lato",
            "typographyFontFamilyType" => "google",
            "typographyFontSize" => 16,
            "typographyFontSizeSuffix" => "px",
            "typographyFontWeight" => 400,
            "typographyLetterSpacing" => 0,
            "typographyLineHeight" => $typography['lineHeight'] ?? 1.9,
            "typographyVariableFontWeight" => 400,
            "typographyFontWidth" => 100,
            "typographyFontSoftness" => 0,
            "typographyBold" => false,
            "typographyItalic" => false,
            "typographyUnderline" => false,
            "typographyStrike" => false,
            "typographyUppercase" => false,
            "typographyLowercase" => false
        ];

        foreach ($bgColor as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function dataTypography($typography = []): BrizyComponent
    {
        $bgColor = [
            "dateTypographyFontStyle" => "",
            "dateTypographyFontFamily" => "lato",
            "dateTypographyFontFamilyType" => "google",
            "dateTypographyFontSize" => 16,
            "dateTypographyFontSizeSuffix" => "px",
            "dateTypographyFontWeight" => 400,
            "dateTypographyLetterSpacing" => 0,
            "dateTypographyLineHeight" => $typography['lineHeight'] ?? 1.9,
            "dateTypographyVariableFontWeight" => 400,
            "dateTypographyFontWidth" => 100,
            "dateTypographyFontSoftness" => 0,
            "dateTypographyBold" => false,
            "dateTypographyItalic" => false,
            "dateTypographyUnderline" => false,
            "dateTypographyStrike" => false,
            "dateTypographyUppercase" => false,
            "dateTypographyLowercase" => false
        ];

        foreach ($bgColor as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function subscribeEventButtonTypography(): BrizyComponent
    {
        $bgColor = [
            "subscribeEventButtonTypographyFontStyle" => "",
            "subscribeEventButtonTypographyFontFamily" => "lato",
            "subscribeEventButtonTypographyFontFamilyType" => "google",
            "subscribeEventButtonTypographyFontSize" => 15,
            "subscribeEventButtonTypographyFontSizeSuffix" => "px",
            "subscribeEventButtonTypographyFontWeight" => 700,
            "subscribeEventButtonTypographyLetterSpacing" => 0,
            "subscribeEventButtonTypographyLineHeight" => 1.6,
            "subscribeEventButtonTypographyVariableFontWeight" => 400,
            "subscribeEventButtonTypographyFontWidth" => 100,
            "subscribeEventButtonTypographyFontSoftness" => 0,
            "subscribeEventButtonTypographyBold" => false,
            "subscribeEventButtonTypographyItalic" => false,
            "subscribeEventButtonTypographyUnderline" => false,
            "subscribeEventButtonTypographyStrike" => false,
            "subscribeEventButtonTypographyUppercase" => false,
            "subscribeEventButtonTypographyLowercase" => false
        ];

        foreach ($bgColor as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function detailButtonTypography(): BrizyComponent
    {
        $bgColor = [
            "detailButtonTypographyFontStyle" => "",
            "detailButtonTypographyFontFamily" => "lato",
            "detailButtonTypographyFontFamilyType" => "google",
            "detailButtonTypographyFontSize" => 15,
            "detailButtonTypographyFontSizeSuffix" => "px",
            "detailButtonTypographyFontWeight" => 700,
            "detailButtonTypographyLetterSpacing" => 0,
            "detailButtonTypographyLineHeight" => 1.6,
            "detailButtonTypographyVariableFontWeight" => 400,
            "detailButtonTypographyFontWidth" => 100,
            "detailButtonTypographyFontSoftness" => 0,
            "detailButtonTypographyBold" => false,
            "detailButtonTypographyItalic" => false,
            "detailButtonTypographyUnderline" => false,
            "detailButtonTypographyStrike" => false,
            "detailButtonTypographyUppercase" => false,
            "detailButtonTypographyLowercase" => false
        ];

        foreach ($bgColor as $key => $value) {
            $this->getValue()->set($key, $value);
        }

        return $this;
    }

    public function addCustomCSS(string $newCSS): BrizyComponent
    {
        $savedCSS = $this->getValue()->get('customCSS');

        if (!empty($savedCSS)) {
            $customCSS = $savedCSS . PHP_EOL . $newCSS;
        } else {
            $customCSS = $newCSS;
        }
        $this->getValue()->set('customCSS', $customCSS);

        return $this;
    }

    public function setMobileBgColorStyle($color, $opacity): BrizyComponent
    {
        if ($color === null || $color === '') {
            $color = $this->getValue()->get('mobileBgColorHex');
        }

        if ($opacity === null || $opacity === '') {
            $opacity = $this->getValue()->get('mobileBgColorOpacity');
        }

        $this->getValue()->set('mobileBgColorHex', $color);
        $this->getValue()->set('mobileBgColorOpacity', $opacity);
        $this->getValue()->set('mobileBgColorPalette', '');

        return $this;
    }

    private function addConstructPadding($padding, $position, $prefix = '', $measureType = 'px'): void
    {
        $formattedPrefix = $prefix !== '' ? strtolower($prefix) : '';

        $formattedPosition = ucfirst(strtolower($position));

        $paddingKey = "Padding{$formattedPosition}";

        $paddingConfig = [
            ($formattedPrefix . ($formattedPrefix ? 'PaddingType' : 'paddingType')) => "ungrouped",
            ($formattedPrefix . ($formattedPrefix ? $paddingKey : lcfirst($paddingKey))) => $padding,
            ($formattedPrefix . ($formattedPrefix ? "{$paddingKey}Suffix" : lcfirst("{$paddingKey}Suffix"))) => $measureType,
        ];

        foreach ($paddingConfig as $key => $value) {
            $this->getValue()->set($key, $value);
        }
    }

    private function addConstructMargin($padding, $position, $prefix = '', $measureType = 'px'): void
    {
        $formattedPrefix = $prefix !== '' ? strtolower($prefix) : '';

        $formattedPosition = ucfirst(strtolower($position));

        $paddingKey = "Margin{$formattedPosition}";

        $paddingConfig = [
            ($formattedPrefix . ($formattedPrefix ? 'MarginType' : 'marginType')) => "ungrouped",
            ($formattedPrefix . ($formattedPrefix ? $paddingKey : lcfirst($paddingKey))) => $padding,
            ($formattedPrefix . ($formattedPrefix ? "{$paddingKey}Suffix" : lcfirst("{$paddingKey}Suffix"))) => $measureType,
        ];

        foreach ($paddingConfig as $key => $value) {
            $this->getValue()->set($key, $value);
        }
    }

    public function mobileSizeTypeOriginal(): BrizyComponent
    {
        $this->getValue()->set('mobileSizeType', 'original');
        return $this;
    }

    public function mobileSize($size = 100, $suffix = '%'): BrizyComponent
    {
        $this->getValue()->set('mobileSize', $size);
        $this->getValue()->set('mobileSizeSuffix', $suffix);
        return $this;
    }

    public function tabletSizeTypeOriginal(): BrizyComponent
    {
        $this->getValue()->set('tabletSizeType', 'original');
        return $this;
    }

    public function sizeTypeOriginal(): BrizyComponent
    {
        $this->getValue()->set('sizeType', 'original');
        return $this;
    }

    public function addHeight(int $int, string $string = 'px'): BrizyComponent
    {
        $this->getValue()->set('height', $int);
        $this->getValue()->set('heightSuffix', $string);
        return $this;
    }

    public function addHeightStyle(int $int = 400, string $string = 'px', string $style = 'custom'): BrizyComponent
    {
        $this->getValue()->set('heightStyle', $style);
        $this->getValue()->set('height', $int);
        $this->getValue()->set('heightSuffix', $string);
        return $this;
    }

    public function addSectionHeight(int $int, string $suffix = 'vh'): BrizyComponent
    {
        $this->getValue()->set('sectionHeight', $int);
        $this->getValue()->set('fullHeight', 'custom');
        $this->getValue()->set('sectionHeightSuffix', $suffix);
        return $this;
    }

    public function addImage($mbSectionItem, $options = [])
    {
        $image = new BrizyImageComponent();
        $wrapperImage = new BrizyWrapperComponent('wrapper--image');

        $imageConfig = [
            'imageSrc' => $mbSectionItem['content'] ?? '',
            'imageFileName' => $mbSectionItem['imageFileName']
        ];

        $imageConfig = array_merge($imageConfig, $options);

        foreach ($imageConfig as $key => $value) {
            $image->getValue()->set($key, $value);
        }

        $wrapperImage->getValue()->add('items', [$image]);

        $this->getValue()->add('items', [$wrapperImage]);
    }

    public function addMenuBorderRadius($radius)
    {
        $this->getValue()->set('menuBorderRadiusType', 'ungrouped');
        $this->getValue()->set('menuBorderRadius', $radius);
        $this->getValue()->set('menuBorderBottomRightRadius', $radius);
        $this->getValue()->set('menuBorderBottomLeftRadius', $radius);
        $this->getValue()->set('menuBorderTopRightRadius', $radius);
        $this->getValue()->set('menuBorderTopLeftRadius', $radius);
    }


    public function addLine(int $width, array $color, int $borderWidth, $options = [], $position = null, $align = 'center')
    {
        try {
            $component = new BrizyLineComponent();
            $wrapperLine = new BrizyWrapperComponent('wrapper--line');

            $component->getValue()->set("width", $width ?? 70);
            $component->getValue()->set("widthSuffix", $options['widthSuffix'] ?? 'px');
            $component->getValue()->set("borderWidth", $borderWidth ?? 1);
            $component->getValue()->set("borderColorHex", $color['color'] ?? '#4e3131');
            $component->getValue()->set("borderColorOpacity", $color['opacity'] ?? 1);

            foreach ($options as $key => $value) {
                $component->getValue()->set($key, $value);
            }

            $wrapperLine->getValue()->add('items', [$component]);
            $wrapperLine->getValue()->set('horizontalAlign', $align);

            $this->getValue()->add('items', [$wrapperLine], $position);

        } catch (exception $e) {
            Logger::instance()->warning('Error on addLine: ' . $e->getMessage() . '');
        }
    }
}
