<?php

define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('STOP_STATISTICS', true);


require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Picktech\Analytics\Controller\Product;
Loader::includeModule("pt.analytics");

$request = Application::getInstance()->getContext()->getRequest();

$promotionId = (int)$request->getPost("promotion_id");
$productId = (int)$request->getPost("product_id");
$status = (string)$request->getPost("status");
$budget = (string)$request->getPost("budget");

$result = \Picktech\Analytics\Entity\VendorActionPriceTable::update($promotionId,
    [
        'UF_STATUS'      => $status,
        'UF_BUDGET'      => $budget,
    ]
);


if ($status === 'run'){
    Product::changePromotionStatus($productId, '1');
}
else{
    Product::changePromotionStatus($productId, 'N');
}