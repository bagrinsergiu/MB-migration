<?php

namespace MBMigration\Builder\Layout\Common\Concern;

use MBMigration\Browser\BrowserPage;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\BrizyComponent\BrizyImageComponent;
use MBMigration\Builder\BrizyComponent\BrizyWrapperComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Builder\Layout\Common\Exception\BrizyKitNotFound;
use MBMigration\Builder\Layout\Common\Exception\BrowserScriptException;

trait DanationsAble
{

    /**
     * Process and add all items the same brizy section
     */
    protected function handleDonations(ElementContextInterface $data, BrowserPage $browserPage, array $brizyKit): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        if(!isset($brizyKit['donation-button'])) {
            throw new BrizyKitNotFound();
        }

        switch ($mbSection['category']) {
            case "donation":
                $brizyDonationButton = new BrizyComponent(json_decode($brizyKit['donation-button'], true));
                $brizyDonationButton->getValue()->set_horizontalAlign(
                    $mbSection['settings']['sections']['donations']['alignment'] ?? 'center'
                );
                $brizyDonationButton->getItemValueWithDepth(0)
                    ->set_text($mbSection['settings']['sections']['donations']['text'] ?? 'MAKE A DONATION')
                    ->set_linkExternal($mbSection['settings']['sections']['donations']['url'] ?? '#');
                $brizySection->getValue()->add_items([$brizyDonationButton]);
                break;
        }


        return $brizySection;
    }
}