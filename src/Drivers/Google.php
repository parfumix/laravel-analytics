<?php

namespace Laravel\Analytics\Drivers;

use Google_Auth_AssertionCredentials;
use Google_Client;
use Google_Service_Analytics;
use Laravel\Analytics\Driver;
use Laravel\Analytics\DriverAble;

class Google extends Driver implements DriverAble {

    /**
     * @var
     */
    protected $viewId;

    /**
     * Set sdk .
     *
     * @return mixed
     */
    public function buildSdk() {
        $client = new Google_Client();
        $client->setApplicationName('Google Analytics Application');
        $client->setAssertionCredentials(
            new Google_Auth_AssertionCredentials(
                $this->get('email_address'),
                array(Google_Service_Analytics::ANALYTICS_READONLY),
                file_get_contents(storage_path(
                    $this->get('certificate')
                ))
            ));

        $client->setClientId(
            $this->get('client_id')
        );

        $client->setAccessType('offline_access');

        $service = new Google_Service_Analytics($client);

        $this->setSdk($service);

        if( $this->has('view_id') )
            $this->setViewId(
                $this->get('view_id')
            );

        return $this;
    }


    /**
     * Set profile ID .
     *
     * @param $viewId
     * @return $this
     */
    public function setViewId($viewId) {
        $this->viewId = 'ga:' . $viewId;

        return $this;
    }

    /**
     * Get profile ID .
     *
     * @return mixed
     */
    public function getViewId() {
        return $this->viewId;
    }

    /**
     * Perform query .
     *
     * @param $start
     * @param $end
     * @param $metrics
     * @param null $viewId
     * @param array $others
     * @return mixed
     */
    protected function performQuery($start, $end, $metrics, $viewId = null, array $others = array()) {
        #@todo use cache for future .
        return $this->getSdk()->data_ga->get(
            isset($viewId) ? $viewId : $this->getviewId(),
            $start,
            $end,
            $metrics,
            $others
        );
    }


    /**
     * Get total visitors for specific period .
     *
     * @param string $start
     * @param string $end
     * @param string $max
     * @return mixed
     */
    public function totalVisitors($start, $end = null, $max = null) {
        if( is_null($end) )
            $end = date('Y-m-d');

        $results = $this->performQuery($start, $end, 'ga:visits', $this->getviewId(), ['dimensions' => 'ga:date']);

        return array_map(function($row) {
            return [$row[0] => $row[1]];
        }, $results->rows);
    }

    /**
     * Get total views for specific period .
     *
     * @param string $start
     * @param string $end
     * @param string $max
     * @return mixed
     */
    public function totalViews($start = '', $end = '', $max = '') {
        $results = $this->performQuery($start, $end, 'ga:pageviews', $this->getviewId(), ['dimensions' => 'ga:date']);

        return array_map(function($row) {
            return [$row[0] => $row[1]];
        }, $results->rows);
    }
}