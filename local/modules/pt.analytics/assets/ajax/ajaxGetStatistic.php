<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;

Loader::includeModule("pt.analytics");

$request     = Application::getInstance()->getContext()->getRequest();

$dateArr = explode(" - " , $request->getPost("date"));

if (count($dateArr) == 2){
    $objDateTimeFrom = new DateTime($dateArr[0]);
    $objDateTimeTo = new DateTime($dateArr[1]);
    $dateTo = $objDateTimeTo->toString();
    $dateFrom    = $objDateTimeFrom->toString();
}
else{
    $objDateTime = new DateTime($dateArr[0]);
    $dateTo = $objDateTime->toString();
    $dateFrom    = $objDateTime->add("-1 day")->toString();
}
$StatisticByDate = new Picktech\Analytics\Controller\Statistic;


$result =  Picktech\Analytics\Services\Statistic::getStatistic((int)$request->getPost("product_id"), $dateFrom, $dateTo);
$result['BYDATE'] = $StatisticByDate->getProductStatisticSortByDate($dateFrom, $dateTo, (int)$request->getPost("product_id"));


echo json_encode($result);
?>