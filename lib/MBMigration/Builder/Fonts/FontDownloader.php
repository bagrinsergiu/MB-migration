<?php

class FontDownloader {

    private $fontUrl;
    private $name;

    public function __construct($fontUrl, $name) {
        $this->fontUrl = $fontUrl;
        $this->name = $name;
    }

    public function downloadFonts(): array
    {
        $cssContent = file_get_contents($this->fontUrl);
        $fontMatches = [];
        $result = [];
        preg_match_all('/url\([\'"]?([^\'"\?\#]+)\??[^\'"\)]*["\']?\)/i', $cssContent, $fontMatches);

        foreach ($fontMatches[1] as $fontUrl) {
            $filename = $this->getFileNameFromURL($fontUrl);
            $filePathMain = $this->normalizePath(__DIR__ );
            $filePathT = $this->normalizePath('/FontSet/' . $this->name);
            $filePath = $filePathMain . $filePathT;
            if (!is_dir($filePath)) {
                mkdir($filePath, 0777, true);
            }
            $filePath .= '/' . $filename;
            $fontData = file_get_contents($fontUrl);
            file_put_contents($filePath, $fontData);
            echo 'Downloaded font: ' . $filePath . PHP_EOL;
            $result[] = $this->normalizePath($filePathT. '/' . $filename);
        }
        return $result;
    }

    private function getFileNameFromURL($url) {
        $path = parse_url($url, PHP_URL_PATH);
        $fileName = basename($path);
        return $fileName;
    }

    private function getFontExtension($fontUrl)
    {
        $extension = pathinfo($fontUrl, PATHINFO_EXTENSION);
        if (empty($extension)) {
            preg_match('/format\(\'(.*?)\'\)/', $fontUrl, $formatMatch);
            $extension = $this->getExtensionFromFormat($formatMatch[1]);
        }
        return $extension;
    }

    private function getExtensionFromFormat($format): string
    {
        switch ($format) {
            case 'embedded-opentype':
                return 'eot';
            case 'woff2':
                return 'woff2';
            case 'woff':
                return 'woff';
            case 'truetype':
                return 'ttf';
            case 'svg':
                return 'svg';
            default:
                return '';
        }
    }

    private function normalizePath($path) {
        $path = str_replace('\\', '/', $path);
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $path = str_replace('/', '\\', $path);
        }
        return $path;
    }
}
