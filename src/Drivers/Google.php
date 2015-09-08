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
    protected $profileId;

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

        if( $this->has('profile_id') )
            $this->setProfileId('profile_id');

        return $this;
    }


    /**
     * Set profile ID .
     *
     * @param $profileId
     * @return $this
     */
    public function setProfileId($profileId) {
        $this->profileId = 'ga:' . $profileId;

        return $this;
    }

    /**
     * Get profile ID .
     *
     * @return mixed
     */
    public function getProfileId() {
        return $this->profileId;
    }
}