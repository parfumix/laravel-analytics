<?php

namespace Laravel\Analytics;

/**
 * Get analytic driver .
 *
 * @param string $driver
 * @return mixed
 */
function analytic($driver = '') {
    return app('analytic-manager')
        ->driver($driver);
}