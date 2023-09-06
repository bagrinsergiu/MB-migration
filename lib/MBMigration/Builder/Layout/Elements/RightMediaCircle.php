<?php

namespace MBMigration\Builder\Layout\Elements;

use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;

class RightMediaCircle extends Element
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
     * @throws \DOMException
     */
    public function getElement(array $elementData = [])
    {
        return $this->RightMediaCircle($elementData);
    }

    /**
     * @throws \DOMException
     */
    protected function RightMediaCircle(array $sectionData) {
        Utils::log('Create bloc', 1,  "right_media_circle");
        $this->cache->set('currentSectionData', $sectionData);
        $decoded = $this->jsonDecode['blocks']['left-media-circle'];
        $block = json_decode($decoded, true);

        $block['value']['items'][0]['value']['bgColorPalette'] = '';

        if($this->checkArrayPath($sectionData, 'settings/color/bg')) {
            $block['value']['items'][0]['value']['bgColorHex'] = strtolower($sectionData['settings']['color']['bg']);
        }
        //$this->marginAndPaddingOffset($block);

        foreach ($sectionData['items'] as $item){
            if($item['category'] == 'photo' && $item['content'] !== ''){
                $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageSrc'] = $item['content'];
                $block['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['items'][0]['value']['imageFileName'] = $item['imageFileName'];
            }
            if($item['category'] == 'text') {
                if($item['item_type']=='title'){
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][0]['value']['items'][0]['value']['text'] = $this->replaceTitleTag($item['content']);
                }
                if($item['item_type']=='body'){
                    if (isset($item['settings']['used_fonts'])){
                        $options = array_merge($options, ['fontFamily' => $item['settings']['used_fonts']['uuid']]);
                    }
                    $block['value']['items'][0]['value']['items'][0]['value']['items'][1]['value']['items'][1]['value']['items'][0]['value']['text'] = $this->replaceParagraphs($item['content']);
                }
            }
        }
        $block = $this->replaceIdWithRandom($block);
        return json_encode($block);
    }
}