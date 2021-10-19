<?php

declare(strict_types=1);

namespace Picktech\Analytics\Entity;

use spaceonfire\BitrixTools\ORM\IblockElement;

/**
 * Class VendorTable
 * @package Picktech\Analytics\Entity
 */
class VendorTable extends IblockElement
{
    /**
     * @inheritDoc
     */

    public static function getIblockCode(): string
    {
        return 'vendor';
    }
}

