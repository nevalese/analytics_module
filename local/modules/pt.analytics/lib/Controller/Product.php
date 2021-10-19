<?php

namespace Picktech\Analytics\Controller;

use Bitrix\Main\Type\Date;
use Picktech\Analytics\Product\ProductFilterDto;
use Picktech\Analytics\Product\ProductReader;
use Picktech\Analytics\Entity\ProductTable;

/**
 * Class Product
 * @package Picktech\Analytics\Controller
 */
class Product {

    public static function changePromotionStatus($productId, $val){
        $productTable = new ProductTable();
        if(!empty($val)){
            \CIBlockElement::SetPropertyValuesEx($productId, $productTable->getIblockId(),['IS_PARTNER' =>$val]);
        }
    }


}