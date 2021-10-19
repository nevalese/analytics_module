<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader;
use Picktech\Analytics\Product\ProductReader;
use Picktech\Analytics\Product\ProductFilterDto;
use Picktech\Analytics\Data\Paginator;

Loader::includeModule("iblock");
Loader::includeModule("pt.analytics");
global $USER;
$vendorId      = trim(strip_tags($_REQUEST['q']));
$itemId = [];

$productReader = new ProductReader();

$productFilter = new ProductFilterDto();
$productFilter->vendorId = $vendorId;
$pageNavigation = new Paginator('product_list');

$productList = $productReader->readAll($productFilter, $pageNavigation->allRecords());

$result = [];

foreach ($productList as $key => $product) {
    $result[$key]['TITLE'] = $product->name;
    $result[$key]['ID']    = $product->id;
}

echo json_encode($result);

?>