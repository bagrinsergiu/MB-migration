<?php

namespace MBMigration\Builder;

use stdClass;

class ItemSetter
{
    private $data;
    private $item;
    private $text;

    public function __construct($json = '')
    {
        if($json !== '') {
            $this->newItem($json);
        }
    }

    public function newItem($json): void
    {
        $this->data = json_decode($json);
        $this->begin();
    }

    public function item(int $id): ItemSetter
    {
        if (isset($this->item->value->items[$id])) {
            $this->item = $this->item->value->items[$id];
        }
        return $this;
    }

    public function setting(string $key, $value, $externalValue = false): void
    {
        if($externalValue) {
            if (isset($this->item->$key)) {
                $this->item->$key = $value;
            } else {
                $this->addParameter($key, $value);
            }
        }
        if (isset($this->item->value->$key)) {
            $this->item->value->$key = $value;
        } else {
            $this->addParameter($key, $value);
        }
        $this->begin();
    }

    public function mainSetting(string $key, $value, $externalValue = false): void
    {
        if($externalValue) {
            if (isset($this->item->$key)) {
                $this->item->$key = $value;
            } else {
                $this->addParameter($key, $value);
            }
        }
        if (isset($this->item->value->$key)) {
            $this->item->value->$key = $value;
        } else {
            $this->addParameter($key, $value);
        }
        $this->begin();
    }

    public function section(int $id): ItemSetter
    {
        if (isset($this->item->items[$id])) {
            $this->item = $this->item->items[$id];
        }
        return $this;
    }

    public function setText($value): void
    {
        $this->text = $value;
        if (isset($this->item->value->text)) {
            $this->item->value->text = $value;
        } else {
            $this->addParameter('text', $this->textContent('FontStyle'));
            $this->addParameter('typographyFontStyle', $this->textContent('FontStyle'));
            $this->addParameter('typographyFontFamily', $this->textContent('FontFamily'));
            $this->addParameter('typographyFontFamilyType', $this->textContent('FontFamilyType'));
            $this->addParameter('typographyFontSize', $this->textContent('FontSize'));
            $this->addParameter('typographyFontSizeSuffix', $this->textContent('FontSizeSuffix'));
            $this->addParameter('typographyFontWeight', $this->textContent('FontWeight'));
            $this->addParameter('typographyLetterSpacing', $this->textContent('LetterSpacing'));
            $this->addParameter('typographyLineHeight', $this->textContent('LineHeight'));
        }
        $this->begin();
    }
    public function addItem(array $value): void
    {
        $value = $this->arrayToObject($value);
        $this->item->value->items[] = $value;
        $this->begin();
    }

    public function get()
    {
       $result = json_decode(json_encode($this->data),true);
       $this->begin();
       return $result;
    }

    private function addParameter(string $key, $value): void
    {
        $this->item->value->$key = $value;
    }

    private function begin(): ItemSetter
    {
        if (isset($this->data->value)) {
            $this->item = $this->data;
        }
        return $this;
    }

    private function mergeObjects($object1, $object2)
    {
        if (is_object($object1) && is_object($object2)) {
            $mergedObject = clone $object1;
            foreach ($object2 as $key => $value) {
                if (isset($mergedObject->$key) && is_object($mergedObject->$key) && is_object($value)) {
                    $mergedObject->$key = $this->mergeObjects($mergedObject->$key, $value);
                } else {
                    $mergedObject->$key = $value;
                }
            }
            return $mergedObject;
        } elseif (is_array($object1) && is_array($object2)) {
            return array_merge($object1, $object2);
        } else {
            return $object2;
        }
    }

    private function arrayToObject($array): stdClass
    {
        if (is_array($array)) {
            $object = new stdClass();
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $object->$key = $this->arrayToObject($value);
                } else {
                    $object->$key = $value;
                }
            }
            return $object;
        } else {
            return $array;
        }
    }

    private function textContent($key)
    {
        if(is_array($this->text)){
            if(array_key_exists($key, $this->text)){
                return $this->text[$key];
            }
        }
        return '';
    }
}