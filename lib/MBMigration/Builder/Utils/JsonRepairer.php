<?php

namespace MBMigration\Builder\Utils;

class JsonRepairer {
    public static function repairJson($jsonString) {
        // Удаляем экранирование обратных слешей
        $jsonString = self::removeBackslashes($jsonString);

        // Попробуем декодировать JSON
        $decodedJson = json_decode($jsonString);

        // Проверим, была ли ошибка при декодировании
        if (json_last_error() !== JSON_ERROR_NONE) {
            // Если есть ошибка, попробуем исправить ее
            switch (json_last_error()) {
                case JSON_ERROR_DEPTH:
                    $errorMessage = 'Достигнута максимальная глубина стека';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $errorMessage = 'Несоответствие состояний (неправильный или некорректный JSON)';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $errorMessage = 'Ошибка управляющего символа, возможно, неверная кодировка';
                    break;
                case JSON_ERROR_SYNTAX:
                    // Если ошибка синтаксиса, попробуем исправить JSON
                    $jsonString = self::fixJsonSyntax($jsonString);
                    break;
                case JSON_ERROR_UTF8:
                    $errorMessage = 'Некорректная кодировка UTF-8, возможно, неправильная кодировка';
                    break;
                default:
                    $errorMessage = 'Неизвестная ошибка при декодировании JSON';
                    break;
            }

            // Если есть ошибка, вы можете выбрать, как обработать эту ошибку.
            // Например, можно выбросить исключение или залогировать сообщение об ошибке.
            // В данном примере мы просто вернем сообщение об ошибке.
            return ['error' => $errorMessage];
        }

        // Если декодирование прошло успешно, вернем декодированные данные
        return $decodedJson;
    }

    public static function fixEscaping($jsonString) {
        // Удалить комментарии
        $jsonString = preg_replace('~/\*.*\*/~sU', '', $jsonString);

        // Удалить строки с двойными слешами перед символами новой строки
        $jsonString = preg_replace('~\\\\n~', '', $jsonString);

        return $jsonString;
    }

    public static function isEscaped($jsonString, $position) {
        // Проверяем, экранирован ли символ на указанной позиции в строке
        $countBackslashes = 0;
        $index = $position - 1;

        while ($index >= 0 && $jsonString[$index] === '\\') {
            $countBackslashes++;
            $index--;
        }

        return $countBackslashes % 2 === 1;
    }

    private static function removeBackslashes($jsonString) {
        return stripslashes($jsonString);
    }

    private static function fixJsonSyntax($jsonString) {
        // В данной реализации мы попробуем исправить простые синтаксические ошибки JSON
        // Это может не сработать во всех случаях, и зависит от конкретной ошибки.

        // Удаляем все символы до открывающей фигурной скобки '{'
        $startIndex = strpos($jsonString, '{');
        if ($startIndex === false) {
            return $jsonString;
        }

        // Удаляем все символы после закрывающей фигурной скобки '}'
        $endIndex = strrpos($jsonString, '}');
        if ($endIndex === false) {
            return $jsonString;
        }

        // Выделяем и исправляем срез строки JSON между '{' и '}'
        $jsonSlice = substr($jsonString, $startIndex, $endIndex - $startIndex + 1);

        // Попробуем декодировать этот срез
        $decodedSlice = json_decode($jsonSlice);

        // Если декодирование удалось, вернем исправленную версию JSON
        if ($decodedSlice !== null) {
            $jsonString = substr_replace($jsonString, json_encode($decodedSlice), $startIndex, $endIndex - $startIndex + 1);
        }

        return $jsonString;
    }
}
