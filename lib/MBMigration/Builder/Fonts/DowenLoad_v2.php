<?php


class FontDownloader_v2
{
    private $fontUrl;
    private $fontData;

    public function __construct($url)
    {
        $this->fontUrl = $url;
        $this->fontData = file_get_contents($this->fontUrl);
    }

    public function downloadFonts($name)
    {
        $fonts = [];
        $fontFaces = $this->parseFontFaces();
        foreach ($fontFaces as $fontFace) {
            $fontUrl = $this->getFontUrl($fontFace);
            $fontFormat = $this->getFontFormat($fontFace);
            $fontFamily = $this->getFontFamily($fontFace);
            $fontWeight = $this->getFontWeight($fontFace);
            $fontStyle = $this->getFontStyle($fontFace);

            $fileName = $this->saveFont($fontUrl, $fontFormat, $name, $fontWeight, $fontStyle);

            $fonts[$fontWeight][$fontStyle][] = $fileName;
        }
        return $fonts;
    }

    private function parseFontFaces()
    {
        $fontFaces = array();
        preg_match_all('/@font-face\s*{([^}]*)}/', $this->fontData, $matches);

        if (isset($matches[0])) {
            $fontFaces = $matches[0];
        }

        return $fontFaces;
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

    private function saveFont($fontUrl, $fontFormat, $fontFamily, $fontWeight, $fontStyle): array
    {
        $fontData = file_get_contents($fontUrl);
        if ($fontData !== false) {
            $directory = './fonts/' . $fontFamily . '/' . $fontWeight . '/' . $fontStyle;
            if (!is_dir($directory)) {
                mkdir($directory, 0777, true);
            }

            $fileName = basename($fontUrl);
            $filePath = $directory . '/' . $fileName;

            file_put_contents($filePath, $fontData);

            echo "Font downloaded: $fileName, Format: $fontFormat" . PHP_EOL;
            return [
                    'fileName' => $fileName,
                    'path'=> $this->removeLeadingDot($filePath)
                ];
        }
        return ['fileName' => ''];
    }
    private function removeLeadingDot($string) {
        if (strlen($string) > 0 && $string[0] === '.') {
            $string = substr($string, 1);
        }
        return $string;
    }
}


