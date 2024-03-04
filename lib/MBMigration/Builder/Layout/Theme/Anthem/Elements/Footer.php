<?php

namespace MBMigration\Builder\Layout\Theme\Anthem\Elements;

use DOMException;
use GuzzleHttp\Exception\GuzzleException;
use MBMigration\Builder\ItemBuilder;
use MBMigration\Builder\VariableCache;
use MBMigration\Core\Utils;
use MBMigration\Layer\Brizy\BrizyAPI;
use MBMigration\Parser\JS;

class Footer extends Element
{
    /**
     * @var VariableCache
     */
    protected $cache;
    private $jsonDecode;
    /**
     * @var BrizyAPI
     */
    private $brizyAPI;

    public function __construct($jsonKitElements, BrizyAPI $brizyAPI)
    {
        $this->cache = VariableCache::getInstance();
        $this->jsonDecode = $jsonKitElements;
        $this->brizyAPI = $brizyAPI;
    }

    /**
     * @throws DOMException
     */
    public function getElement(array $elementData = []): bool
    {
        return $this->Footer();
    }

    /**
     * @throws DOMException
     * @throws \Exception
     * @throws GuzzleException
     */
    protected function Footer(): bool
    {
        \MBMigration\Core\Logger::instance()->info('Create Footer');

        $sectionData = $this->cache->get('mainSection')['footer'];

        $options = [];

        $imageAdd = false;

        $objBlock = new ItemBuilder();
        $objText = new ItemBuilder();
        $objImage = new ItemBuilder();
        $objColum = new ItemBuilder();
        $objIcon = new ItemBuilder();

        $decoded = $this->jsonDecode['blocks']['footer'];

        $objBlock->newItem($decoded['main']);
        $objText->newItem($decoded['item-text']);
        $objImage->newItem($decoded['item-image']);
        $objColum->newItem($decoded['item-empty']);

        $this->generalParameters($objBlock, $options, $sectionData);

        $objBlock->setting('bgColorHex', $sectionData['style']['background-color']);
        $objBlock->setting('bgColorOpacity', $sectionData['style']['opacity']);

        if ($this->checkArrayPath($sectionData, 'settings/background/photo')) {
            $imageAdd = true;
            $objImage->item()->item()->setting('imageSrc', $sectionData['settings']['background']['photo']);
            $objImage->item()->item()->setting('imageFileName', $sectionData['settings']['background']['filename']);
            $objImage->item()->item()->setting('sizeType', 'custom');
            $objImage->item()->item()->setting('size', 100);
            $objImage->item()->item()->setting('width', 80);
            $objImage->item()->item()->setting('widthSuffix', "%");
            $objImage->item()->item()->setting('height', 100);
            $objImage->item()->item()->setting('heightSuffix', "%");
        }

        foreach ($sectionData['items'] as $item) {
            if ($item['category'] == 'text') {
                $this->setOptionsForUsedFonts($item, $options);
                $this->defaultTextPosition($item, $options);

                $this->textCreation($item, $objBlock);
            }
        }

        $block = $this->replaceIdWithRandom($objBlock->get());


        $position = '{"align":"bottom","top":1,"bottom":1}';
        $rules = '[{"type":1,"appliedFor":null,"entityType":"","entityValues":[]}]';

        $this->brizyAPI->createGlobalBlock(json_encode($block), $position, $rules);

        $this->cache->set('footerBlock', json_encode($block));
        $this->cache->set('footerBlockCreated', true);

        return true;
    }

    private function iconColumnCreation($IconItems, $objIcon, $objColum, $decoded)
    {
        foreach ($IconItems as $item) {
            $iconName = $this->getDataIconValue($item['content']);
            $objIcon->newItem($decoded['item']);
            $objIcon->setting('linkExternal', $item['href']);
            $objIcon->setting('name', $this->getIcon($iconName['icon']));
            $objColum->item()->addItem($objIcon->get());
        }
    }

    /**
     * @throws \Exception
     */
    private function textCreation($sectionData, $objBlock)
    {
        $i = 0;
        foreach ($sectionData['brzElement'] as $textItem) {
            switch ($textItem['type']) {
                case 'EmbedCode':
                    if (!empty($sectionData['content'])) {
                        $embedCode = $this->findEmbeddedPasteDivs($sectionData['content']);
                        if (is_array($embedCode)) {
                            $objBlock->addItem($this->embedCode($embedCode[$i]));
                        }
                        $i++;
                    }
                    break;
                case 'Cloneable':
                case 'Wrapper':
                    $objBlock->addItem($textItem);
                    break;
            }
        }
    }

}