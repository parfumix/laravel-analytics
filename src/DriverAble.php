<?php

namespace Laravel\Analytics;

interface DriverAble {

    /**
     * Set sdk .
     *
     * @param $sdk
     * @return mixed
     */
    public function setSdk($sdk);

    /**
     * Get skd .
     *
     * @return mixed
     */
    public function getSdk();

    /**
     * Get total visitors for specific period .
     *
     * @param string $start
     * @param string $end
     * @param string $max
     * @return mixed
     */
    public function totalVisitors($start, $end = null, $max = null);

    /**
     * Get total views for specific period .
     *
     * @param string $start
     * @param string $end
     * @param string $max
     * @return mixed
     */
    public function totalViews($start, $end = null, $max = null);
}