<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use Picktech\Analytics\Services\Statistic;

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

$statisticByDate = $StatisticByDate->getProductStatisticSortByDate($dateFrom, $dateTo, (int)$request->getPost("product_id"));

    $conversionByDate = [];
    foreach ($statisticByDate as $key=> $date){
        $conversionByDate[$key]['DATE'] =  $date['DATE'];
        $conversionByDate[$key] = $date;
        if ((int)$request->getPost("type") === 1) {
           if ($date['COUNT_SHOW'] >0) $conversionByDate[$key]['CONVERSION'] = round(((int)$date['COUNT_VIEW']/ (int)$date['COUNT_SHOW'])*100);
           else $conversionByDate[$key]['CONVERSION'] =0;
        }
        if ((int)$request->getPost("type") === 2) {
            if ($date['COUNT_SHOW'] >0)  $conversionByDate[$key]['CONVERSION'] =  round(((int)$date['COUNT_CLICK']/(int)$date['COUNT_SHOW'])* 100);
            else $conversionByDate[$key]['CONVERSION'] =0;
        }
        if ((int)$request->getPost("type") === 3) {
            if ($date['COUNT_VIEW'] >0)  $conversionByDate[$key]['CONVERSION'] = round(((int)$date['COUNT_CLICK']/(int)$date['COUNT_VIEW'])*100);
            else $conversionByDate[$key]['CONVERSION'] =0;
        }

    }

echo json_encode($conversionByDate);
