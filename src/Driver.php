<?php

namespace Laravel\Analytics;

use Flysap\Support\Traits\AttributesTrait;

abstract class Driver {

    use AttributesTrait;

    /**
     * @var
     */
    protected $sdk;

    public function __construct(array $options) {
        $this->setAttributes($options);

        $this->buildSdk();
    }

    /**
     * Set sdk .
     *
     * @return mixed
     */
    abstract public function buildSdk();

    /**
     * Set sdk .
     *
     * @param $sdk
     * @return $this
     */
    public function setSdk($sdk) {
        $this->sdk = $sdk;

        return $this;
    }

    /**
     * Get skd instance .
     *
     * @return mixed
     */
    public function getSdk() {
        return $this->sdk;
    }
}