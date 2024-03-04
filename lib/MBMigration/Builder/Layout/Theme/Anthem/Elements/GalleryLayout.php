<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class GalleryLayout extends Element
{
    /**
     * @var VariableCache
     */
    protected $cache;

    private $jsonDecode;

    public function __construct($jsonKitElements)
    {
        $this->cache = VariableCache::getInstance();
        $this->jsonDecode = $jsonKitElements;
    }

    /**
     * @throws \Exception
     */
    public function getElement($elementData)
    {
        return $this->gallery_layout($elementData);
    }

    protected function gallery_layout(array $sectionData)
    {
        $bodyBgColor = '#ffffff';
        $rotatorSpeed = 5;

        $objBlock = new ItemBuilder();
        $objSlide = new ItemBuilder();

        \MBMigration\Core\Logger::instance()->info('Create bloc');
        $this->cache->set('currentSectionData', $sectionData);

        $sectionData['items'] = $this->sortByOrderBy($sectionData['items']);

        $decoded = $this->jsonDecode['blocks']['gallery-layout'];

        $objBlock->newItem($decoded['main']);
        $objSlide->newItem($decoded['itemSlide']);

        $block = json_decode($decoded['main'], true);
        $slide = json_decode($decoded['item'], true);

        if (isset($sectionData['settings']['sections']['gallery']['transition']) && $sectionData['settings']['sections']['gallery']['transition'] !== 'Slide') {
            $objBlock->setting('sliderTransition', 'off');

        } else {
            $objBlock->setting('sliderTransition', 'on');
            $objBlock->setting('sliderAutoPlaySpeed', $rotatorSpeed);

        }

        if (!empty($sectionData['style']['body']['background-color'])) {
            $bodyBgColor = $sectionData['style']['body']['background-color'];
        }

        $colorArrows = $this->getContrastColor($bodyBgColor);

        $objBlock->setting('sliderArrowsColorHex', $colorArrows);
        $objBlock->setting('sliderArrowsColorOpacity', 1);
        $objBlock->setting('sliderArrowsColorPalette', '');

        $objBlock->setting('hoverSliderArrowsColorHex', '#7f7c7c');
        $objBlock->setting('hoverSliderArrowsColorOpacity', 1);
        $objBlock->setting('hoverSliderArrowsColorPalette', '');

        foreach ($sectionData['items'] as $item) {
            if (!$item['uploadStatus']) {
                continue;
            }
            $objSlide->newItem($decoded['itemSlide']);

            if (!empty($item['link'])) {
                $sectionItem = [];
                if ($item['new_window']) {
                    $sectionItem['new_window'] = 'on';
                } else {
                    $sectionItem['new_window'] = 'off';
                }

                switch ($this->detectLinkType($item['link'])) {
                    case 'mail':
                        $objSlide->item()->item()->setting('linkType', 'external');
                        $objSlide->item()->item()->setting('linkExternal', 'mailto:'.$item['link']);
                        $objSlide->item()->item()->setting('linkExternalBlank', $sectionItem['new_window']);
                        break;
                    case 'phone':
                        $objSlide->item()->item()->setting('linkType', 'external');
                        $objSlide->item()->item()->setting('linkExternal', 'tel:'.$item['link']);
                        $objSlide->item()->item()->setting('linkExternalBlank', $sectionItem['new_window']);
                        break;
                    case 'string':
                    case 'link':
                        $urlComponents = parse_url($item['link']);

                        if (!empty($urlComponents['host'])) {
                            $slash = '';
                        } else {
                            $slash = '/';
                        }

                        $objSlide->item()->item()->setting('linkType', 'external');
                        $objSlide->item()->item()->setting('linkExternal', $slash.$item['link']);
                        $objSlide->item()->item()->setting('linkExternalBlank', $sectionItem['new_window']);
                        break;
                    default:
                        $objSlide->item()->item()->setting('linkType', 'external');
                        $objSlide->item()->item()->setting('linkExternal', $slash.$item['link']);
                        $objSlide->item()->item()->setting('linkExternalBlank', $sectionItem['new_window']);
                        break;
                }
            }

            $imageWidth = 1500;
            $imageHeight = 650;

            if (!empty($sectionData['settings']['sections']['gallery']['max_width']) &&
                !empty($sectionData['settings']['sections']['gallery']['max_height'])) {

                $imageWidth = $sectionData['settings']['sections']['gallery']['max_width'];
                $imageHeight =$sectionData['settings']['sections']['gallery']['max_height'];

            } else {
                if (!empty($sectionData['settings']['layout']['gallery']['max_width']) &&
                    !empty($sectionData['settings']['layout']['gallery']['max_height'])) {

                    $imageWidth = $sectionData['settings']['layout']['gallery']['max_width'];
                    $imageHeight = $sectionData['settings']['layout']['gallery']['max_height'];
                }
            }

//            $objSlide->item()->item()->setting('imageWidth', $imageWidth );
//            $objSlide->item()->item()->setting('imageHeight', $imageHeight);

            $widthPercent = $this->determine_image_orientation($imageWidth, $imageHeight);
            $objSlide->item()->item()->setting('width', $widthPercent);

            $objSlide->item()->item()->setting('imageSrc', $item['content']);
            $objSlide->item()->item()->setting('imageFileName', $item['imageFileName']);
            $objSlide->setting('customCSS', 'element{background:' . $bodyBgColor . '}');

            $this->insertElementAtPosition($block, 'value/items', $slide);

            $objBlock->addItem($objSlide->get());
        }
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }

    private function getContrastColor($hexColor)
    {
        $hexColor = str_replace('#', '', $hexColor);

        $r = hexdec(substr($hexColor, 0, 2));
        $g = hexdec(substr($hexColor, 2, 2));
        $b = hexdec(substr($hexColor, 4, 2));

        $brightness = (($r * 299) + ($g * 587) + ($b * 114)) / 1000;

        return $brightness > 125 ? '#000000' : '#FFFFFF';
    }

    private function determine_image_orientation($width, $height)
    {
        $percentage_difference = abs(($width - $height) / max($width, $height)) * 100;

        if ($this->checkInRange($percentage_difference, 25, 100)) {
            return 100;
        } elseif ($this->checkInRange($percentage_difference, -25, -100)) {
            return 25;
        } elseif ($this->checkInRange($percentage_difference, 25, -25)) {
            return 65;
        } else {
            return 100;
        }
    }

    private function checkInRange($number, $startInterval = 10, $endInterval = -10)
    {
        if ($number <= $startInterval && $number >= $endInterval) {
            return true;
        } else {
            return false;
        }
    }


}