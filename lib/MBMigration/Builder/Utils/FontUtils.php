<?php

namespace MBMigration\Builder\Utils;

class FontUtils
{
    public static function convertFontFamily($fontName): string
    {
        $inputString = explode(',',  $fontName);

        $inputString = str_replace(["\"", "'", ' '], ['', '', '_'], $inputString[0]);

        return strtolower($inputString);
    }

    public static function transliterateFontFamily($fontName): string
    {
        $inputString = str_replace(["\"", "'", ' '], ['', '', '_'], $fontName);

        $inputString = str_replace(',', '', $inputString);

        return strtolower($inputString);
    }

    /**
     * Полная нормализация font-family аналогично JavaScript нормализации
     * Преобразует "Oswald, 'Oswald Light', sans-serif" в "oswald_oswald_light_sans_serif"
     * 
     * @param string $fontFamily Полная строка font-family
     * @return string Нормализованный ключ
     */
    public static function normalizeFontFamilyFull($fontFamily): string
    {
        // Удаляем кавычки и запятые, заменяем пробелы на подчеркивания
        $normalized = str_replace(["\"", "'", ','], ['', '', ''], $fontFamily);
        $normalized = str_replace(' ', '_', trim($normalized));
        
        return strtolower($normalized);
    }

    /**
     * Получает первую часть font-family (до запятой)
     * Преобразует "Oswald, 'Oswald Light', sans-serif" в "oswald"
     * 
     * @param string $fontFamily Полная строка font-family
     * @return string Первая часть нормализованная
     */
    public static function normalizeFontFamilyFirst($fontFamily): string
    {
        return self::convertFontFamily($fontFamily);
    }

    /**
     * Извлекает все части font-family и создает нормализованные ключи
     * "Oswald, 'Oswald Light', sans-serif" -> ["oswald", "oswald_light"]
     * 
     * @param string $fontFamily Полная строка font-family
     * @return array Массив нормализованных ключей из всех частей (без generic families)
     */
    public static function extractFontFamilyParts($fontFamily): array
    {
        $genericFamilies = ['sans-serif', 'serif', 'monospace', 'cursive', 'fantasy'];
        $parts = explode(',', $fontFamily);
        $keys = [];
        
        foreach ($parts as $part) {
            $part = trim($part);
            // Удаляем кавычки
            $part = str_replace(["\"", "'"], '', $part);
            $part = trim($part);
            
            // Пропускаем generic families
            if (in_array(strtolower($part), $genericFamilies)) {
                continue;
            }
            
            // Нормализуем: заменяем пробелы на подчеркивания и приводим к нижнему регистру
            $normalized = str_replace(' ', '_', $part);
            $normalized = strtolower($normalized);
            
            if (!empty($normalized) && !in_array($normalized, $keys)) {
                $keys[] = $normalized;
            }
        }
        
        return $keys;
    }
}
