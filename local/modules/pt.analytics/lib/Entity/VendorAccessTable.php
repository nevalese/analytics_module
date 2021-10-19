<?php

namespace Picktech\Analytics\Entity;

use spaceonfire\BitrixTools\ORM\BaseHighLoadBlockDataManager;

/**
 * Class AnalyticsTable
 * @package Picktech\Analytics\Entity
 */

class VendorAccessTable extends BaseHighLoadBlockDataManager{

    /**
     * @inheritDoc
     */

    public static function getHLId(): string
    {
        return 'VendorAccessAnalytics';
    }
}