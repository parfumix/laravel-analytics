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

/**
 * Get total visitors .
 *
 * @param string $driver
 * @param string $start
 * @param string $end
 * @param string $max
 * @return mixed
 */
function total_visitors($driver = '', $start = '', $end = '', $max = '') {
    return analytic($driver)
        ->totalVisitors($start, $end, $max);
}

/**
 * Get total views for specific period .
 *
 * @param string $driver
 * @param string $start
 * @param string $end
 * @param string $max
 * @return mixed
 */
function total_views($driver = '', $start = '', $end = '', $max = '') {
    return analytic($driver)
        ->totalViews($start, $end, $max);
}