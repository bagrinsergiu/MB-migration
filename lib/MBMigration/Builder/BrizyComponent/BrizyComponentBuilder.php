<?php

namespace MBMigration\Builder\BrizyComponent;

use MBMigration\Builder\BrizyComponent\Components\AbstractComponent;
use MBMigration\Builder\BrizyComponent\Components\ComponentSection;
use MBMigration\Builder\Layout\Common\Exception\BadJsonProvided;
use MBMigration\Core\Logger;

class BrizyComponentBuilder extends AbstractComponent
{
    private BrizyComponent $brizySectionGrid;
    private BrizyComponent $brizyRow;
    private BrizyComponent $brizyColumn;
    private BrizyComponent $brizySection;
    private BrizyComponent $brizyImage;


    /**
     * @throws BadJsonProvided
     */
    public function createSection(): ComponentSection
    {
        $startTime = microtime(true);
        Logger::instance()->info('BrizyComponentBuilder::createSection called', [
            'brizy_kit_available' => isset($this->brizyKit),
            'start_time' => $startTime
        ]);

        try {
            $section = new ComponentSection($this->brizyKit);
            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000; // Convert to milliseconds

            Logger::instance()->info('ComponentSection created successfully', [
                'execution_time_ms' => round($executionTime, 2),
                'component_class' => get_class($section)
            ]);

            return $section;
        } catch (BadJsonProvided $e) {
            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000;

            Logger::instance()->error('Failed to create ComponentSection', [
                'execution_time_ms' => round($executionTime, 2),
                'error_message' => $e->getMessage(),
                'error_class' => get_class($e)
            ]);
            throw $e;
        } catch (\Throwable $e) {
            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000;

            Logger::instance()->error('Unexpected error creating ComponentSection', [
                'execution_time_ms' => round($executionTime, 2),
                'error_message' => $e->getMessage(),
                'error_class' => get_class($e)
            ]);
            throw $e;
        }
    }

    /**
     * @throws BadJsonProvided
     */
    public function createRow(): BrizyComponent
    {
        $startTime = microtime(true);
        Logger::instance()->info('BrizyComponentBuilder::createRow called', [
            'brizy_kit_available' => isset($this->brizyKit),
            'has_global_row' => isset($this->brizyKit['global']['Row']),
            'start_time' => $startTime
        ]);

        try {
            $rowData = json_decode($this->brizyKit['global']['Row'], true);

            if ($rowData === null && json_last_error() !== JSON_ERROR_NONE) {
                Logger::instance()->error('JSON decode error for Row component', [
                    'json_error' => json_last_error_msg(),
                    'raw_data_length' => strlen($this->brizyKit['global']['Row'] ?? '')
                ]);
                throw new BadJsonProvided('Invalid JSON for Row component: ' . json_last_error_msg());
            }

            $component = new BrizyComponent($rowData);
            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000;

            Logger::instance()->info('BrizyComponent Row created successfully', [
                'execution_time_ms' => round($executionTime, 2),
                'component_type' => $component->getType(),
                'component_class' => get_class($component)
            ]);

            return $component;
        } catch (BadJsonProvided $e) {
            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000;

            Logger::instance()->error('Failed to create BrizyComponent Row', [
                'execution_time_ms' => round($executionTime, 2),
                'error_message' => $e->getMessage(),
                'error_class' => get_class($e)
            ]);
            throw $e;
        } catch (\Throwable $e) {
            $endTime = microtime(true);
            $executionTime = ($endTime - $startTime) * 1000;

            Logger::instance()->error('Unexpected error creating BrizyComponent Row', [
                'execution_time_ms' => round($executionTime, 2),
                'error_message' => $e->getMessage(),
                'error_class' => get_class($e)
            ]);
            throw $e;
        }
    }



}
