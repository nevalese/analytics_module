<?php

namespace Picktech\Analytics\Entity;

use spaceonfire\BitrixTools\ORM\BaseHighLoadBlockDataManager;

/**
 * Class DailyStatisticTable
 * @package Picktech\Analytics\Entity
 */

class DailyStatisticTable extends BaseHighLoadBlockDataManager{

    /**
     * @inheritDoc
     */

    public static function getHLId(): string
    {
        return 11;
    }
}