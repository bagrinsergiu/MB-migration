<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use Exception;
use MBMigration\Core\Logger;
use DOMException;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;

class FullMedia extends Element
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
     * @throws DOMException
     */
    public function getElement(array $elementData = [])
    {
        return $this->FullMedia($elementData);
    }

    /**
     * @throws DOMException
     * @throws Exception
     */
    protected function FullMedia(array $sectionData)
    {
        Logger::instance()->info('Create full media');

        $objBlock = new ItemBuilder();

        $options = [];

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['full-media']['main'];
        $general = $this->jsonDecode['blocks']['full-media'];
        $blockImage = $this->jsonDecode['blocks']['full-media']['image'];

        $objBlock->newItem($decoded);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->defaultOptionsForElement($general, $options);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundColor($objBlock, $sectionData, $options);

        $this->backgroundImages($objBlock, $sectionData, $options);

        $this->setOptionsForTextColor($sectionData, $options);

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] == 'title' && $this->showHeader($sectionData)) {

                    $this->textCreation($item, $objBlock);

                }
            }
        }
        
        foreach ($sectionData['items'] as $item) {
            if($item['item_type']=='body' && $this->showBody($sectionData)) {
                $this->textCreation($item, $objBlock);
            }
        }
            
        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'photo' && !empty($item['content'])) {
                $imageOptions = [
                    'imageSrc' => $item['content'],
                    'imageFileName' => $item['imageFileName']
                ];

                if (!empty($item['link'])) {
                    $imageOptions = array_merge($imageOptions, [
                        'linkType' => 'external',
                        'linkExternal' => $item['link']
                    ]);
                }
                $objBlock->item()->item()->item()->addItem($this->wrapperImage($imageOptions, $blockImage));
            }
        }
        
        $block = $this->replaceIdWithRandom($objBlock->get());
        return json_encode($block);
    }


    /**
     * @throws Exception
     */
    private function textCreation($sectionData, $objBlock)
    {
        $i = 0;
        foreach ($sectionData['brzElement'] as $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    if(!empty($sectionData['content'])) {
                        $embedCode = $this->findEmbeddedPasteDivs($sectionData['content']);
                        if(!empty($embedCode)){
                            $objBlock->item()->item()->item()->addItem($this->embedCode($embedCode[$i]));
                        }
                        $i++;
                    }
                    break;
                case 'Cloneable':
                case 'Wrapper':
                    $objBlock->item()->item()->item()->addItem($textItem);
                    break;
            }
        }
    }
}