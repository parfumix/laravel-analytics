<?php

namespace Laravel\Analytics;

use Illuminate\Support\Facades\Facade;

class Analytics extends Facade {

    public static function getFacadeAccessor() {
        return 'analytic-manager';
    }
}