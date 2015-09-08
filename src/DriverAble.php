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
}