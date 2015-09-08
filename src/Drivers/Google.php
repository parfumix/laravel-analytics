<?php

namespace Laravel\Analytics\Drivers;

use Google_Auth_AssertionCredentials;
use Google_Client;
use Google_Service_Analytics;
use Laravel\Analytics\Driver;
use Laravel\Analytics\DriverAble;

class Google extends Driver implements DriverAble {

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

        return $this;
    }
}