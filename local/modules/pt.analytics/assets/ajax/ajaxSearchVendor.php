<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;
use  \Bitrix\Iblock\ElementTable;
Loader::includeModule("iblock");

global $USER;
    $q        = trim(strip_tags($_REQUEST['q']));
    $itemId   = [];

    $vendorList = ElementTable::getList(
        [
            'select' => ['ID', 'NAME'],
            'filter'=>['IBLOCK_ID'=>6, '%NAME' => $q ]
        ]
    )->fetchAll();

        $result =[];

        foreach ($vendorList as $key=>$vendor) {
            $result[$key]['TITLE'] = $vendor['NAME'];
            $result[$key]['ID'] = $vendor['ID'];
        }

    echo json_encode($result);

?>