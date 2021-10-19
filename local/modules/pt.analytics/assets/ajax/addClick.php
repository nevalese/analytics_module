<?php

define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('STOP_STATISTICS', true);


require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Bitrix\Main\Loader;
use Picktech\Analytics\Statistic\StatisticWriter;
use Bitrix\Main\Application;

Loader::includeModule("pt.analytics");

$request = Application::getInstance()->getContext()->getRequest();



StatisticWriter::writeClick((int)$request->getPost("product_id"), (string)$request->getPost("refer"), (string)$request->getPost("type"));