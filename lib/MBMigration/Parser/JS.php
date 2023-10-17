<?php

namespace MBMigration\Parser;

use MBMigration\Core\Utils;
use MBMigration\Parser\JsParse\JSCode;
use Nesk\Puphpeteer\Puppeteer;

class JS
{
    private static $CODE;
    private static $url;

    public static function StylesColorExtractor(int $sectionID, $pageUrl, array $styleProperties = [])
    {

        Utils::log('Styles Extractor', 1, "StylesExtractor");
        $data = [
            'selector' => '[data-id="' . $sectionID . '"]',
            'styleProperties' => json_encode(['background-color', 'opacity'])
        ];

        if (!empty($styleProperties)) {
            $data['styleProperties'] = json_encode($styleProperties);
        }

        self::$CODE = JSCode::StylesExtractor($data);
        self::$url = $pageUrl;

        $Color = self::Run($sectionID);

        return self::convertColor($Color['style']['background-color']);
    }

    public static function StylesPaddingExtractor(int $sectionID, $pageUrl, array $styleProperties = []): array
    {
        Utils::log('Styles Extractor', 1, "StylesExtractor");

        $result = ['padding-bottom' => 15, 'padding-top' => 15, 'padding-left' => 0, 'padding-right' => 0];

        $properties = ['padding-bottom', 'padding-top', 'padding-left', 'padding-right'];

        $data = [
            'selector' => '[data-id="' . $sectionID . '"]',
            'styleProperties' => json_encode($properties)
        ];

        if (!empty($styleProperties)) {
            $data['styleProperties'] = json_encode($styleProperties);
        }

        self::$CODE = JSCode::StylesExtractor($data);
        self::$url = $pageUrl;

        $padding = self::Run($sectionID);

        if (!empty($padding['error'])) {
            Utils::MESSAGES_POOL($padding['error'], $sectionID, 'JS:RUN [error]');
        }
        if (!empty($padding['style'])) {
            Utils::MESSAGES_POOL('success', $sectionID, 'JS:RUN');

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

        Utils::log('Styles Extractor From Menu', 1, "StylesExtractor");

        $data = [
            'selector' => '[data-id="' . $sectionID . '"]',
            'families' => json_encode($fontFamilies)
        ];

        self::$CODE = JSCode::ExtractStyleFromMenu($data);
        self::$url = $pageUrl;

        $result = self::Run($sectionID);

        if (!empty($result['warns'])) {
            Utils::MESSAGES_POOL($result['warns'], $sectionID, 'JS:RUN [error]');
        }
        if (!empty($result['menu'])) {
            Utils::MESSAGES_POOL('success', $sectionID, 'JS:RUN');
            return $result['menu'];
        }
        return [];
    }


    public static function RichText($blockID, $pageUrl, $fontFamilies = [])
    {
        $data = [
            'data' => [
                'selector' => '[data-id="' . $blockID . '"]',
                'attributes' => ["font-size", "font-family", "font-weight", "text-align", "letter-spacing", "text-transform"],
                'families' => [
                    "proxima_nova_proxima_nova_regular_sans-serif" => "uid1111",
                    "helvetica_neue_helveticaneue_helvetica_arial_sans-serif" => "uid2222"
                ],
                'defaultFontFamily' => 'helvetica_neue_helveticaneue_helvetica_arial_sans-serif'
            ]
        ];

        if (!empty($fontFamilies)) {
            $data['data']['families'] = $fontFamilies;
        }

        self::$CODE = JSCode::RichText($data);
        self::$url = $pageUrl;

        $RichText = self::Run($blockID);

        if (!empty($RichText['warns'])) {
            Utils::MESSAGES_POOL($RichText['warns'], $blockID, 'JS:RUN [warns]');
        }
        if (!empty($RichText['error'])) {
            Utils::MESSAGES_POOL($RichText['error'], $blockID, 'JS:RUN [error]');
        }
        if (!empty($RichText['text'])) {
            Utils::MESSAGES_POOL('success', $blockID, 'JS:RUN');
            return $RichText['text'];
        }
        if (!empty($RichText['embeds'])) {
            Utils::MESSAGES_POOL('success', $blockID, 'JS:RUN');
            return $RichText['embeds'];
        }
        return '';
    }

    private static function Run($id)
    {
        if (empty(self::$CODE)) {
            Utils::MESSAGES_POOL('JS:CODE is empty', $id, 'JS:RUN');
            return '';
        }
        try {
            Utils::log('style parse from page', 1, "getStylesFromSection");
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
                    ]]);

            $page = $browser->newPage();

            $page->goto(self::$url);

            $stylesHandle = $page->evaluateHandle(self::$CODE);

            $result = $stylesHandle->jsonValue();

            $browser->close();

            return json_decode($result, true);
        } catch (\Exception $e) {
            Utils::MESSAGES_POOL($e->getMessage(), $id, 'JS:RUN');
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
            return sprintf("#%02X%02X%02X", $r, $g, $b);
        }

        preg_match_all('/\d+/', $color, $matches);

        if (count($matches[0]) !== 3) {
            return false;
        }

        list($r, $g, $b) = $matches[0];

        return sprintf("#%02X%02X%02X", $r, $g, $b);
    }
}