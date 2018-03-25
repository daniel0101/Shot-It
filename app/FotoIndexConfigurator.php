<?php

namespace App;

use ScoutElastic\IndexConfigurator;
use ScoutElastic\Migratable;

class FotoIndexConfigurator extends IndexConfigurator
{
    use Migratable;

    protected $settings = [
        //
    ];
}