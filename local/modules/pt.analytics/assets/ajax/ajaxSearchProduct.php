<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
use  \Bitrix\Iblock\ElementTable;
Loader::includeModule("iblock");

global $USER;
$q        = trim(strip_tags($_REQUEST['q']));
$itemId   = [];

$prosuctList = ElementTable::getList(
    [
        'select' => ['ID', 'NAME'],
        'filter'=>['IBLOCK_ID'=>1, '%NAME' => $q ]
    ]
)->fetchAll();

$result =[];

foreach ($prosuctList as $key=>$product) {
    $result[$key]['TITLE'] = $product['NAME'];
    $result[$key]['ID'] = $product['ID'];
}

echo json_encode($result);

?>