<?php

namespace Picktech\Analytics;

use spaceonfire\BitrixTools;
use spaceonfire\BMF\Module as BMFModule;

class Module extends BMFModule
{
    public function __construct()
    {
        BitrixTools\Common::loadModules([
            'iblock',
            'sale',
            'highloadblock'
        ]);

        parent::__construct([
            'MODULE_ID' => 'pt.analytics',
            'MODULE_VERSION' => '1.0.0',
            'MODULE_VERSION_DATE' => '2021-03-15',
        ]);
    }
}
