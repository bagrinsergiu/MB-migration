<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use DOMException;
use Exception;
use MBMigration\Builder\Utils\TextTools;
use MBMigration\Core\Logger;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;

class FullText extends Element
{
    /**
     * @var VariableCache
     */
    protected $cache;
    private $jsonDecode;

    /**
     * @var array
     */
    protected $sectionData;

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
        $this->sectionData = $elementData;
        return $this->FullText($elementData);
    }

    /**
     * @throws DOMException
     * @throws Exception
     */
    protected function FullText(array $sectionData)
    {
        Logger::instance()->info('Create bloc');

        $options = [];

        $objBlock = new ItemBuilder();
        $objLine = new ItemBuilder();

        $this->cache->set('currentSectionData', $sectionData);

        $decoded = $this->jsonDecode['blocks']['full-text'];

        $objBlock->newItem($decoded['main']);
        $objLine->newItem($decoded['line']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $this->backgroundParallax($objBlock, $sectionData);

        $this->backgroundColor($objBlock, $sectionData);

        $this->backgroundImages($objBlock, $sectionData);

        $this->backgroundVideo($objBlock, $sectionData);

        $this->setOptionsForTextColor($sectionData, $options);

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] === 'title' && $this->showHeader($sectionData)) {
                    $this->textCreation($item, $objBlock, $sectionData['style']);
                    $objBlock->item(0)->addItem($this->wrapperLine(
                        [
                            'borderColorHex' => $sectionData['style']['border']['border-bottom-color'] ?? ''
                        ]
                    ));
                }
            }
        }

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                if ($item['item_type'] === 'body' && $this->showBody($sectionData)) {
                    $this->textCreation($item, $objBlock, $sectionData['style']);
                }
            }
        }

        if ($sectionData['category'] == 'donation' && $this->checkArrayPath($sectionData, 'settings/sections/donations')) {
            if ($sectionData['settings']['sections']['donations']['new_window'] ?? false) {
                $sectionItem['new_window'] = 'on';
            } else {
                $sectionItem['new_window'] = 'off';
            }
            if (isset($sectionData['settings']['sections']['donations']['url'])) {
                $buttonOptions = [
                    'linkExternal' => $sectionData['settings']['sections']['donations']['url'],
                    'text' => TextTools::transformText(
                        $sectionData['settings']['sections']['donations']['text'] ?? $sectionData['settings']['layout']['donations']['text'],
                        $sectionData['style']['donation']['button']['text-transform'] ?? 'normal'),
                    'linkExternalBlank' => $sectionItem['new_window'],

                    'bgColorHex' => $sectionData['style']['donation']['button']['background-color'] ?? '#024E69',
                    'hoverBgColorHex' => $sectionData['style']['donation']['button']['background-color'] ?? '#024E69',

                    'borderStyle' => 'none',
                    'hoverBorderStyle' => 'none',
                ];
                $position = $sectionData['settings']['sections']['donations']['alignment'] ?? 'left';

                $objBlock->item()->addItem($this->button($buttonOptions, $position));
            }
            if (isset($sectionData['settings']['sections']['donations']['text'])) {
                // to do
            }
        }

        return json_encode($this->replaceIdWithRandom($objBlock->get()));
    }

    /**
     * @throws Exception
     */
    private function textCreation($sectionData, $objBlock, $style)
    {
        $i = 0;
        foreach ($sectionData['brzElement'] as $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    if(!empty($sectionData['content'])) {
                        $embedCode = $this->findEmbeddedPasteDivs($sectionData['content']);
                        if(is_array($embedCode)){
                            $objBlock->item(0)->addItem($this->embedCode($embedCode[$i]));
                        }
                        $i++;
                    }
                    break;
                case 'Cloneable':
                case 'Wrapper':
                    $objBlock->item(0)->addItem($textItem);
                    break;
            }
        }
    }
}