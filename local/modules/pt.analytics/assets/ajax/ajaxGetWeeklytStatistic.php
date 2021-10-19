<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;
use Picktech\Analytics\Services\Statistic;

Loader::includeModule("pt.analytics");

$objDateTime = new DateTime($dateArr[0]);
$dateTo = $objDateTime->toString();
$dateFrom  = $objDateTime->add("-7 day")->toString();


$params = [
    'filter' => [
        'IBLOCK_ID'=>CATALOG_IBLOCK_ID
    ],
    'select'=> [
        'ID'
    ]
];

$rs = \Bitrix\Iblock\ElementTable::getList($params);

$statisticWeekly = ['VIEWS'=>0, 'CLICKS'=>0, 'SHOWS'=>0];

while($r = $rs->fetch())
{
    $jsonStatistic = json_decode(Statistic::getStatistic((int)$r['ID'], $dateFrom, $dateTo));
    $statisticWeekly['VIEWS'] += $jsonStatistic['VIEWS'];
    $statisticWeekly['CLICKS'] += $jsonStatistic['CLICKS'];
    $statisticWeekly['SHOWS'] += $jsonStatistic['SHOWS'];
}

return json_encode($statisticWeekly);

?>