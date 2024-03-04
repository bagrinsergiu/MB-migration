<?php

namespace MBMigration\Parser;

use MBMigration\Core\Utils;
use MBMigration\Parser\JsParse\JSCode;
use Nesk\Puphpeteer\Puppeteer;

class JS
{
    private static $CODE;
    private static $url;

    public static function StylesColorExtractor($sectionID, $pageUrl, array $styleProperties = [])
    {
        \MBMigration\Core\Logger::instance()->info('Styles Extractor');
        $properties = ['background-color', 'opacity', 'border-bottom-color'];
        $result = ['background-color' => '#ffffff', 'opacity' => 1];

        if (is_array($sectionID)) {
            $selector = '[data-id="'.$sectionID[0].'"] .'.$sectionID[1];
        } else {
            $selector = '[data-id="'.$sectionID.'"]';
        }
        $data = [
            'selector' => $selector,
            'styleProperties' => json_encode($properties),
        ];


        if (!empty($styleProperties)) {
            $data['styleProperties'] = json_encode($styleProperties);
        }

        self::$CODE = JSCode::StylesExtractor($data);
        self::$url = $pageUrl;

        $returned = self::Run($sectionID);

        if (!empty($returned['error'])) {
            return false;
        }

        $style = $returned['style'];
        $opacityIsSet = false;
        foreach ($properties as $key) {
            if (array_key_exists($key, $style)) {
                $convertedData = self::convertColor(trim($style[$key], 'px'));
                if (is_array($convertedData)) {
                    $result[$key] = $convertedData['color'];
                    $result['opacity'] = $convertedData['opacity'];
                    $opacityIsSet = true;
                } else {
                    if ($opacityIsSet && $key == 'opacity') {
                        continue;
                    } else {
                        $result[$key] = $convertedData;
                    }
                }
            }
        }

        return $result;
    }

    public static function imageStylesExtractor(int $sectionID, $pageUrl)
    {
        \MBMigration\Core\Logger::instance()->info('Image Styles Extractor');
        $data = [
            'selector' => '[data-id="'.$sectionID.'"]',
        ];

        self::$CODE = JSCode::ImageStyles($data);
        self::$url = $pageUrl;

        $returned = self::Run($sectionID);

        if (!empty($returned[0]['width'])) {
            return $returned[0]['width'];
        }

        return 360;
    }

    public static function StylesPaddingExtractor(int $sectionID, $pageUrl, array $styleProperties = []): array
    {
        \MBMigration\Core\Logger::instance()->info('Styles Extractor');

        $result = ['padding-bottom' => 15, 'padding-top' => 15, 'padding-left' => 0, 'padding-right' => 0];

        $properties = ['padding-bottom', 'padding-top', 'padding-left', 'padding-right'];

        $data = [
            'selector' => '[data-id="'.$sectionID.'"]',
            'styleProperties' => json_encode($properties),
        ];

        if (!empty($styleProperties)) {
            $data['styleProperties'] = json_encode($styleProperties);
        }

        self::$CODE = JSCode::StylesExtractor($data);
        self::$url = $pageUrl;

        $padding = self::Run($sectionID);

        if (!empty($padding['error'])) {
            \MBMigration\Core\Logger::instance()->info($padding['error']);
        }
        if (!empty($padding['style'])) {
            \MBMigration\Core\Logger::instance()->info('success');

            $style = $padding['style'];

            foreach ($properties as $key) {
                if (array_key_exists($key, $style)) {
                    $result[$key] = trim($style[$key], 'px');
                }
            }
        }

        return $result;
    }

    public static function stylesMenuExtractor(int $sectionID, $pageUrl, array $fontFamilies)
    {

        \MBMigration\Core\Logger::instance()->info('Styles Extractor From Menu');

        $data = [
            'selector' => '[data-id="'.$sectionID.'"]',
            'families' => json_encode($fontFamilies),
        ];

        self::$CODE = JSCode::ExtractStyleFromMenu($data);
        self::$url = $pageUrl;

        $result = self::Run($sectionID);


        if (!empty($result['warns'])) {
            \MBMigration\Core\Logger::instance()->info($result['warns']);
        }
        if (!empty($result['menu'])) {
            \MBMigration\Core\Logger::instance()->info('success');

            return $result['menu'];
        }

        return [];
    }


    public static function RichText($blockID, $pageUrl, $fontFamilies = [])
    {
        $DefaultFontFamilies = null;
        $kitFontFamilies = [];

        foreach ($fontFamilies as $key => $value) {
            if ($key === 'Default') {
                $DefaultFontFamilies = $value;
            } else {
                $kitFontFamilies[$key] = $value;
            }
        }

        $data = [
            'data' => [
                'selector' => '[data-id="'.$blockID.'"]',
                'attributes' => [
                    "font-size",
                    "font-family",
                    "font-weight",
                    "text-align",
                    "letter-spacing",
                    "text-transform",
                    "line-height",
                ],
                'families' => $kitFontFamilies,
                'defaultFontFamily' => $DefaultFontFamilies,
            ],
        ];

        self::$CODE = JSCode::RichText($data);
        self::$url = $pageUrl;

        $RichText = self::Run($blockID);

        if (!empty($RichText['warns'])) {
            \MBMigration\Core\Logger::instance()->info($RichText['warns']);
        }

        if (!empty($RichText['error'])) {
            \MBMigration\Core\Logger::instance()->info($RichText['error']);
        } else {
            \MBMigration\Core\Logger::instance()->info('success');
        }

        if (!empty($RichText['text']) && (empty($RichText['embeds']) && empty($RichText['icons']) && empty($RichText['buttons']))) {
            return $RichText['text'];
        } else {
            return [
                'text' => $RichText['text'],
                'embeds' => $RichText['embeds'],
                'icons' => $RichText['icons'],
                'buttons' => $RichText['buttons'],
            ];
        }
    }

    private static function Run($id)
    {
        if (empty(self::$CODE)) {
            \MBMigration\Core\Logger::instance()->info('JS:CODE is empty');

            return '';
        }
        try {
            \MBMigration\Core\Logger::instance()->info('style parse from page');
            $puppeteer = new Puppeteer();
            $browser = $puppeteer->launch([
                "headless" => "new",
                'args' =>
                    [
                        '--no-sandbox',
                        '--disable-setuid-sandbox',
                        '--disable-dev-shm-usage',
                        '--aggressive-cache-discard',
                        '--disable-cache',
                        '--disable-application-cache',
                        '--disable-offline-load-stale-cache',
                        '--disable-gpu-shader-disk-cache',
                        '--media-cache-size=0',
                        '--disk-cache-size=0',
                    ],
            ]);

            $page = $browser->newPage();

            $page->goto(self::$url);

            $stylesHandle = $page->evaluateHandle(self::$CODE);

            $result = $stylesHandle->jsonValue();

            $browser->close();

            return json_decode($result, true);
        } catch (\Exception $e) {
            \MBMigration\Core\Logger::instance()->info($e->getMessage());

            return '';
        }
    }

    private static function convertColor($color)
    {
        if (preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $color)) {
            return $color;
        }

        if (preg_match('/rgba\((\d+), (\d+), (\d+), ([0-9]*\.?[0-9]+)\)/', $color, $matches)) {
            $r = $matches[1];
            $g = $matches[2];
            $b = $matches[3];
            $a = $matches[4];

            $color = sprintf("#%02X%02X%02X", $r, $g, $b);

            if ($a == 0 && $color === "#000000") {
                return '#ffffff';
            } else {
                return [
                    'color' => sprintf("#%02X%02X%02X", $r, $g, $b),
                    'opacity' => $a,
                ];
            }
        }

        if (preg_match_all('/\d+/', $color, $matches)) {
            if (count($matches[0]) !== 3) {
                return $color;
            }
            list($r, $g, $b) = $matches[0];

            return sprintf("#%02X%02X%02X", $r, $g, $b);
        }

        return $color;
    }
}