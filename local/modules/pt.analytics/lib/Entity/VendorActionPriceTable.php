<?php

namespace Picktech\Analytics\Entity;

use spaceonfire\BitrixTools\ORM\BaseHighLoadBlockDataManager;

/**
 * Class AnalyticsTable
 * @package Picktech\Analytics\Entity
 */

class VendorActionPriceTable extends BaseHighLoadBlockDataManager{

    /**
     * @inheritDoc
     */

    public static function getHLId(): string
    {
        return 	7;
    }
}