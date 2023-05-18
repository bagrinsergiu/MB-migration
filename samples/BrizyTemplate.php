<?php

use Brizy\layer\Brizy\BrizyAPI;

require_once(__DIR__ . '/../src/Core/Core.php');

$brizyAPI   = new BrizyAPI();

//получаем полный список Workspaces  json
//var_dump($brizyAPI->getWorkspaces());

//возвращает только id Workspaces по его имени или возвращет false если ничего не найдено
//var_dump($brizyAPI->getWorkspaces(Config::$nameMigration));

//создаем пользователя возвращвет токен пользователя
//var_dump($brizyAPI->createUser()); //to do

//создаем Workspaces и возвращаем его id
//var_dump($brizyAPI->createdWorkspaces());

//создаем проетк с именеи в worksapce и возвращем его id
//var_dump($brizyAPI->createProject('Project_migration', 4303835, 'id'));

//получаем полный список проектов или ищим проект по названию и получаем его id
//var_dump($brizyAPI->getProject(4303835));

//получаем полный список проектов или ищим проект по названию и получаем его id
//var_dump($brizyAPI->getPage(4303928));

//получаем полный список проектов или ищим проект по названию и получаем его id
//var_dump($brizyAPI->createPage(4303928, 'Project_migration'));

//создаем меню
//var_dump($brizyAPI->createMenu(4303928, 'about'));

//получаем token для работы с проектом с помощью API graphQL
//var_dump($brizyAPI->getGraphToken(4303928));

//скачиваем картинку по ссылки и загружаем в Brizy
//$urlImage = "https://s3.amazonaws.com/media.cloversites.com/55/55afd3c5-e660-4611-b111-392f24015bfe/gallery/slides/5c45f7bf-39c5-4cfc-af3c-e463f6cca210.jpg";
//var_dump($brizyAPI->createMedia($urlImage));