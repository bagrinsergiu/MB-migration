<?php

namespace MBMigration\Builder\Layout\Common\Concern;

trait SlugAble
{
    protected function createSlug($string): string
    {
        $string = trim($string);

        $pattern = [
            '$(à|á|â|ã|ä|å|À|Á|Â|Ã|Ä|Å|æ|Æ)$',
            '$(è|é|é|ê|ë|È|É|Ê|Ë)$',
            '$(ì|í|î|ï|Ì|Í|Î|Ï)$',
            '$(ò|ó|ô|õ|ö|ø|Ò|Ó|Ô|Õ|Ö|Ø|œ|Œ)$',
            '$(ù|ú|û|ü|Ù|Ú|Û|Ü)$',
            '$(ñ|Ñ)$',
            '$(ý|ÿ|Ý|Ÿ)$',
            '$(ç|Ç)$',
            '$(ð|Ð)$',
            '$(ß)$'
        ];

        $replacement = [
            'a',
            'e',
            'i',
            'o',
            'u',
            'n',
            'y',
            'c',
            'd',
            's'
        ];

        $string =  preg_replace($pattern, $replacement, $string);

        $string = htmlentities($string, ENT_QUOTES, "UTF-8");

        $search = [
            '@(&(#?))[a-zA-Z0-9]{1,7}(;)@'
        ];

        $replace = array(
            ''
        );

        $string = preg_replace($search, $replace, $string);

        $search = [
            '@<script[^>]*?>.*?</script>@si',
            '@<[\/\!]*?[^<>]*?>@si',
            '@(\s-\s)@',
            '@[\s]{1,99}@',
            '@—@',
            '@[\\\/)({}^\@\[\]|!#$%*+=~`?.,_;:]@'
        ];

        $replace = [
            '',
            '',
            '-',
            '-',
            '-',
            ''
        ];

        $string = preg_replace($search, $replace, $string);

        return strtolower($string);
    }
}
