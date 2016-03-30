<?php

namespace Geolocation\AdminBundle\Domain\Api;


use Geocoder\Provider\GoogleMaps;
use Geolocation\AdminBundle\Entity\User;
use Ivory\HttpAdapter\CurlHttpAdapter;

class ApiLib
{
    public static function searchAdresse(User $user) {
        $curl = new CurlHttpAdapter();
        $geocoder = new GoogleMaps($curl);
        try {
            return $geocoder->geocode($user->getAdresse() . ', ' . $user->getCodePostal() . ', ' . $user->getVille());
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function verifCp($cp) {
        if (preg_match("/^[0-9]{5,5}$/", $cp)) {
            return true;
        } else {
            return false;
        }
    }
}