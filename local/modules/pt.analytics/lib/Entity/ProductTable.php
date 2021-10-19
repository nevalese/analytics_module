<?php

declare(strict_types=1);

namespace Picktech\Analytics\Entity;

use spaceonfire\BitrixTools\ORM\IblockElement;

/**
 * Class ProductTable
 * @package Picktech\Analytics\Entity
 */
class ProductTable extends IblockElement
{
    /**
     * @inheritDoc
     */

    public static function getIblockCode(): string
    {
        return 'catalog';
    }
}

