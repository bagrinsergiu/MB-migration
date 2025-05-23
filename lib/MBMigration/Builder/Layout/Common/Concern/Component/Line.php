<?php

namespace MBMigration\Builder\Layout\Common\Concern\Component;

use Exception;
use MBMigration\Browser\BrowserPageInterface;
use MBMigration\Builder\BrizyComponent\BrizyComponent;
use MBMigration\Builder\Layout\Common\ElementContextInterface;
use MBMigration\Core\Logger;

trait Line
{
    protected function handleLine(
        BrizyComponent          $line,
        ElementContextInterface $data,
        BrowserPageInterface    $browserPage,
        string                  $selector = null,
                                $options = null,
        array                   $customStyles = []
    ): BrizyComponent
    {
        $mbSection = $data->getMbSection();
        $brizySection = $data->getBrizySection();

        if (!isset($brizyKit['donation-button'])) {
            Logger::instance()->critical('The BrizyKit does not contain the key: donation-button', [$mbSection['typeSection']]);
        }

        try {
            switch ($mbSection['category']) {
                case "button":

                    if (self::$buttonCache['id'] === $options) {
                        $brizySection->getValue()->add_items([self::$buttonCache['button']]);
                        break;
                    }

                    $selector = $selector ?? '[data-id="' . $mbSection['id'] . '"]';

                    $brizyButton = new BrizyComponent(json_decode($brizyKit['donation-button'], true));

                    $brizyButton = $this->setButtonStyles(
                        $brizyButton,
                        $browserPage,
                        $selector,
                        $data,
                        $mbSection
                    );

                    $brizyButton = $this->setHoverButtonStyles(
                        $brizyButton,
                        $browserPage,
                        $selector,
                        $data,
                        $mbSection
                    );

                    $this->setCustomStyles($customStyles, $brizyButton);

                    self::$buttonCache['id'] = $options;
                    self::$buttonCache['button'] = $brizyButton;
                    $brizySection->getValue()->add_items([$brizyButton]);
                    break;
            }
        } catch (Exception $e) {
            Logger::instance()->critical($e->getMessage(), [$mbSection['typeSection']]);
        }

        return $brizySection;
    }

}
