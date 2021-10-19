<?php
declare(strict_types=1);

namespace Picktech\Analytics\Entity;

use spaceonfire\BitrixTools\ORM\IblockPropSimple;

/**
 * Class ProductPropSimpleTable
 * @package Picktech\Analytics\Entity
 */
class ProductPropSimpleTable extends IblockPropSimple
{
    /**
     * @return int
     */
    public static function getIblockId(): int
    {
        return ProductTable::getIblockId();
    }
}
