<?php

class FontDownloader_v3 {
    private $cssFileUrl;

    public function __construct($cssFileUrl) {
        $this->cssFileUrl = $cssFileUrl;
    }

    public function downloadFonts($name): array
    {
        $fontFiles = [];
        $tempFolderPath = 'fontsKit';
        if (!file_exists($tempFolderPath)) {
            mkdir($tempFolderPath);
        }

        $cssContent = file_get_contents($this->cssFileUrl);

        $pattern = '/@font-face\s*{([^}]*)}/';
        preg_match_all($pattern, $cssContent, $matches);

        if (isset($matches[0])) {
            $fontFaces = $matches[0];

            foreach ($fontFaces as $fontFace) {

                $fontFamily = $this->getFontFamily($fontFace);
                $fontWeight = $this->getFontWeight($fontFace);
                if($fontWeight === 'normal'){
                    $fontWeight = 400;
                } else if($fontWeight === 'bold'){
                    $fontWeight = 700;
                }
                $fontStyle = $this->getFontStyle($fontFace);

                $fontFamilyFolderPath = $tempFolderPath . '/' . $name;
                if (!file_exists($fontFamilyFolderPath)) {
                    mkdir($fontFamilyFolderPath);
                }

                $fontFolderPath = $fontFamilyFolderPath . '/' . $fontWeight . '-' . $fontStyle;
                if (!file_exists($fontFolderPath)) {
                    mkdir($fontFolderPath);
                }

                $fontsUrl = $this->extractFontFaceUrls($fontFace);

                foreach ($fontsUrl as $fontUrl) {

                    $fontFilename = basename($fontUrl);
                    $fontFilePath = $fontFolderPath . '/' . $fontFilename;
                    file_put_contents($fontFilePath, file_get_contents($fontUrl));
                    echo "Download -> name: $fontFamily, file: $fontFilename, weight: $fontWeight, style: $fontStyle \n";

                    $fontFiles[$fontWeight][$fontStyle][] = $fontFilePath;
                }
            }
        }
        echo "DONE!\n";
        return $fontFiles;
    }

    function extractFontFaceUrls($css): array
    {
        $pattern = '/@font-face\s*{[^}]*src:\s*([^;]+);/i';
        preg_match_all($pattern, $css, $matches);

        $urls = [];

        if (!empty($matches[1])) {
            foreach ($matches[1] as $match) {
                $urlPattern = '/url\([\'"]?([^\'"\?\#]+)\??[^\'"\)]*["\']?\)/i';
                preg_match_all($urlPattern, $match, $urlMatches);
                $urls = array_merge($urls, $urlMatches[1]);
            }
        }

        return $urls;
    }

    private function getFontUrl($fontFace)
    {
        preg_match('/url\([\'"]?([^\'"\?\#]+)\??[^\'"\)]*["\']?\)/i', $fontFace, $matches);
        $fontUrl = isset($matches[1]) ? $matches[1] : '';

        return $fontUrl;
    }

    private function getFontFormat($fontFace)
    {
        preg_match('/format\(\'([^\']+)\'\)/', $fontFace, $matches);
        $fontFormat = isset($matches[1]) ? $matches[1] : '';

        return $fontFormat;
    }

    private function getFontFamily($fontFace)
    {
        preg_match('/font-family:\s*\'([^\']+)\'/', $fontFace, $matches);
        $fontFamily = isset($matches[1]) ? $matches[1] : '';

        return $fontFamily;
    }

    private function getFontWeight($fontFace)
    {
        preg_match('/font-weight:\s*([^\s;]+)/', $fontFace, $matches);
        $fontWeight = isset($matches[1]) ? $matches[1] : '';

        return $fontWeight;
    }

    private function getFontStyle($fontFace)
    {
        preg_match('/font-style:\s*([^\s;]+)/', $fontFace, $matches);
        $fontStyle = isset($matches[1]) ? $matches[1] : '';

        return $fontStyle;
    }

}