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
    protected function performQuery($start, $end = '', $metrics, $viewId = null, array $others = array()) {
        if( is_null($end) )
            $end = date('Y-m-d');

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
     * Perform real time query .
     *
     * @param $metrics
     * @param null $viewId
     * @param array $others
     * @return mixed
     */
    protected function performRealTimeQuery($metrics, $viewId = null, array $others = []) {
        return $this->getSdk()->data_realtime->get(
            isset($viewId) ? $viewId : $this->getviewId(),
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
        $results = $this->performQuery($start, $end, 'ga:visits', $this->getviewId(), ['dimensions' => 'ga:date']);

        return array_map(function($row) {
            return [$row[0] => $row[1]];
        }, (array)$results->rows);
    }

    /**
     * Get total views for specific period .
     *
     * @param string $start
     * @param string $end
     * @param string $max
     * @return mixed
     */
    public function totalViews($start, $end = null, $max = null) {
        $results = $this->performQuery($start, $end, 'ga:pageviews', $this->getviewId(), ['dimensions' => 'ga:date']);

        return array_map(function($row) {
            return [$row[0] => $row[1]];
        }, (array)$results->rows);
    }

    /**
     * Get top browsers .
     *
     * @param $start
     * @param null $end
     * @return array
     */
    public function topBrowsers($start, $end = null) {
        $results = $this->performQuery($start, $end, 'ga:sessions', $this->getviewId(), ['dimensions' => 'ga:browser', 'sort' => '-ga:sessions']);

        return array_map(function($row) {
            return ['browser' => $row[0], 'sessions' => $row[1]];
        }, (array)$results->rows);
    }

    /**
     * Get most visited pages .
     *
     * @param $start
     * @param null $end
     * @param null $max
     * @return array
     */
    public function mostVisitedPages($start, $end = null, $max = null) {
        $results = $this->performQuery($start, $end, 'ga:pageviews', $this->getviewId(), ['dimensions' => 'ga:pagePath', 'sort' => '-ga:pageviews', 'max-results' => $max]);

        return array_map(function($row) {
            return ['url' => $row[0], 'pageViews' => $row[1]];
        }, (array)$results->rows);
    }

    /**
     * Get top keywords .
     *
     * @param $start
     * @param null $end
     * @param null $max
     * @return array
     */
    public function topKeyWords($start, $end = null, $max = null) {
        $results = $this->performQuery($start, $end, 'ga:sessions', $this->getviewId(), ['dimensions' => 'ga:keyword', 'sort' => '-ga:sessions', 'max-results' => $max, 'filters' => 'ga:keyword!=(not set);ga:keyword!=(not provided)']);

        return array_map(function($row) {
            return ['keyword' => $row[0], 'sessions' => $row[1]];
        }, (array)$results->rows);
    }

    /**
     * Get active users .
     *
     * @param array $others
     * @return mixed
     */
    public function activeUsers($others = array()) {
        $results = $this->performRealTimeQuery('rt:activeUsers', $others);

        return $results;
    }
}