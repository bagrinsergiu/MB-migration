<?php

namespace Brizy\Builder\Layout\Bloom;

class SectionBuilder
{
    private mixed $global;
    private mixed $buildSection;

    public function __construct()
    {
        $file = __DIR__.'\blocksKit.json';
        $fileContent = file_get_contents($file);
        $blocks = json_decode($fileContent, true);
        $this->global = $blocks['global'];
    }

    public function row(){
        $this->buildSection = json_decode($this->global['Row'], true);
    }

    public function column(){

        $this->buildSection['items'];

    }

}



$sb = new SectionBuilder();

$sb->row();

$a=1+1;

