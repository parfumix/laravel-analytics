<?php

namespace Laravel\Analytics;

use Illuminate\Support\ServiceProvider;
use Flysap\Support;

class AnalyticsServiceProvider extends ServiceProvider {

    /**
     * Publish resources.
     */
    public function boot() {
        $this->publishes([
            __DIR__.'/../assets/configuration' => config_path('yaml/analytics'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register() {

    }

    /**
     * Load configuration .
     *
     * @return $this
     */
    protected function loadConfiguration() {
        Support\set_config_from_yaml(
            __DIR__ . '/../configuration/general.yaml' , 'laravel-analytics'
        );

        return $this;
    }
}