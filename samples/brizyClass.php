<?php
namespace Brizy;
require_once(__DIR__ . '/../module/core.php');

$brizyAPI   = new BrizyAPI();

//получаем полный список Workspaces  json
//var_dump($brizyAPI->getWorkspaces());

//возвращает только id Workspaces по его имени или возвращет false если ничего не найдено
//var_dump($brizyAPI->getWorkspaces(Config::$nameMigration));

//создаем Workspaces и возвращаем его id
//var_dump($brizyAPI->createdWorkspaces());

//создаем проетк с именеи в worksapce и возвращем его id
//var_dump($brizyAPI->createProject('Project', 4303835, 'id'));

//получаем полный список проектов
var_dump($brizyAPI->getProject( 4269659, 'fsdfsdf'));

//получаем token для работы с проектом с помощью API graphQL
//var_dump($brizyAPI->getGraphToken(4303858));