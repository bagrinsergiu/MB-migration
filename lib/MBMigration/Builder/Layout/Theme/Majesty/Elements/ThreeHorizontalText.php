<?php
namespace MBMigration\Builder\Layout\Theme\Majesty\Elements;

    use MBMigration\Builder\BrizyComponent\BrizyComponent;
    use MBMigration\Builder\Layout\Common\Concern\DanationsAble;
    use MBMigration\Builder\Layout\Common\Concern\RichTextAble;
    use MBMigration\Builder\Layout\Common\Concern\SectionStylesAble;
    use MBMigration\Builder\Layout\Common\Element\AbstractElement;
    use MBMigration\Builder\Layout\Common\ElementContextInterface;

class ThreeHorizontalText extends AbstractElement
{
    use RichTextAble;
    use SectionStylesAble;
    use DanationsAble;

    public function transformToItem(ElementContextInterface $data): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = new BrizyComponent(json_decode($this->brizyKit['main'], true));
        $brizySection->getValue()->set_marginTop(0);

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));

        $rowJson = json_decode($this->brizyKit['row'], true);
        $itemJson = json_decode($this->brizyKit['item'], true);
        $itemJson = json_decode($this->brizyKit['item'], true);

        $brizySectionRow = new BrizyComponent($rowJson);

        foreach ($mbSection['items'] as $mbItem) {
            switch ($mbItem['category']) {
                case 'photo':
                    $brizySectionItem = new BrizyComponent($itemJson);

                    $brizySectionItem->getItemWithDepth(0, 0)->getValue()
                        ->set_imageSrc($mbItem['content'])
                        ->set_imageFileName($mbItem['imageFileName'])
                        ->set_imageExtension($mbItem['settings']['slide']['extension']);

                    $brizySectionRow->getValue()->add_items([$brizySectionItem]);
                    break;
            }
        }

        $brizySection->getItemValueWithDepth(0)->add_items([$brizySectionRow]);

            foreach ($mbSection['items'] as $mbItem) {
                switch ($mbItem['group']) {
                    case '0':
                        // if the text is not shown in the header or body, skip it
                        if ((!$this->canShowHeader($mbItem) && $mbItem['item_type'] == 'body') ||
                            (!$this->canShowHeader($mbItem) && $mbItem['item_type'] == 'title')) {
                            break;
                        }

                        // add the text on the left side of th bock
                        $elementContext = $data->instanceWithBrizyComponentAndMBSection(
                            $mbItem,
                            $brizySection->getItemWithDepth(0)
                        );
                        $this->handleRichTextItem(
                            $elementContext,
                            $this->browserPage
                        );
                        break;
                    case '1':
                        if ((!$this->canShowHeader($mbItem) && $mbItem['item_type'] == 'body') ||
                            (!$this->canShowHeader($mbItem) && $mbItem['item_type'] == 'title')) {
                            break;
                        }

                        break;
                    case '2':
                        if ((!$this->canShowHeader($mbItem) && $mbItem['item_type'] == 'body') ||
                            (!$this->canShowHeader($mbItem) && $mbItem['item_type'] == 'title')) {
                            break;
                        }

                        break;
                    case '3':
                        if ((!$this->canShowHeader($mbItem) && $mbItem['item_type'] == 'body') ||
                            (!$this->canShowHeader($mbItem) && $mbItem['item_type'] == 'title')) {
                            break;
                        }

                        break;
                }
            }

//        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));
//        $this->handleDonations($elementContext, $this->browserPage, $this->brizyKit);

        $elementContext = $data->instanceWithBrizyComponent($brizySection->getItemWithDepth(0));
        $this->handleSectionStyles($elementContext, $this->browserPage);

        return $brizySection;
    }

}