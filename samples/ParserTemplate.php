<?php

use Brizy\Parser\Parser;

require_once(__DIR__ . '/../src/Core.php');

/**
 * Создаем класс и указываем id проекта с которым будем набоать
 */
$PR = new Parser(299);

/**
 * Получаем информацию о сайте
 */
//var_dump($PR->getSite());

/**
 * Получаем список всех родительских страниц сайта: возвращает id, slug, name
 */
//var_dump($PR->getParentPages());


/**
 * Получаем список всех блоков от на родительской страницы: возвращает массив id, position
 */
//var_dump($PR->getChildFromPages(3463));

/**
 * Получаем список секций страницы
 */
//var_dump($PR->getSectionsPage(4093));


/**
 * Получаем элименты в секциии по id
 * есть два режима работы:
 *  false - сборщик отключон, функция возвращает массив со всеми элиметами секции,
 *  true  - сборщик включон, функция возвращает многомерный массив где в родительские элименты вложенны его дочернии элименты и отсортированные
 */
var_dump($PR->getSectionsItems(104422, true));





