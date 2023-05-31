<?php
$file = __DIR__.'\tsconfig.json';
$fileContent = file_get_contents($file);

$fileContent = json_decode($fileContent, true);


$fileContent = $fileContent['blocks']['full-text']['main'];


interface QueryBuilder
{
    public function section(int $id): QueryBuilder;
    public function item(int $id): QueryBuilder;
    public function settings(string $key, string $value): QueryBuilder;
}

class builder implements QueryBuilder
{
    private mixed $data;
    private mixed $item;

    public function __construct($json){
        $this->data = json_decode($json);
    }
     public function section(int $id): QueryBuilder
     {
         if (isset($this->data->items[$id])) {
             $this->item = $this->data->items[$id];
         }
         if (isset($this->data->value)) {
             $this->item = $this->data->value;
         }
         return $this;
     }

    public function item(int $id): QueryBuilder
    {
        if (isset($this->item->value->items[$id])) {
            $this->item = $this->item->value->items[$id];
        }
        if (isset($this->item->items[$id]->value)) {
            $this->item = $this->item->items[$id]->value;
        }
        return $this;
    }

    public function settings(string $key, string $value): QueryBuilder
    {
        if (isset($this->item->$key)) {
            $this->item->$key = $value;
        }
        else{
            $this->addParameter($key, $value);
        }
        return $this;
    }

    private function addParameter(string $key, $value): QueryBuilder
    {
        $this->item->$key = $value;
        return $this;
    }
}


$builder = new builder($fileContent);

$obj = $builder->section(0)->item(0)->settings('bgColorHex', '#000001')->settings('bgColorHe2', '#000003');
$a='s';