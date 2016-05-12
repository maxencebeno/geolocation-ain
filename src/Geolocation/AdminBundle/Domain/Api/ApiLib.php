<?php

namespace Geolocation\AdminBundle\Domain\Api;


use Geocoder\Provider\GoogleMaps;
use Geolocation\AdminBundle\Entity\Adresse;
use Geolocation\AdminBundle\Entity\User;
use Ivory\HttpAdapter\CurlHttpAdapter;

class ApiLib
{
    public static function searchAdresse(User $user = null, Adresse $adresse = null)
    {
        $curl = new CurlHttpAdapter();
        $geocoder = new GoogleMaps($curl);
        if ($user !== null) {
            try {
                return $geocoder->geocode($user->getAdresse() . ', ' . $user->getCodePostal() . ', ' . $user->getVille());
            } catch (\Exception $e) {
                return false;
            }
        } else {
            try {
                return $geocoder->geocode($adresse->getAdresse() . ', ' . $adresse->getCodePostal() . ', ' . $adresse->getVille());
            } catch (\Exception $e) {
                return false;
            }
        }
    }

    public static function verifCp($cp)
    {
        if (preg_match("/^[0-9]{5,5}$/", $cp)) {
            return true;
        } else {
            return false;
        }
    }

    public static function dateToMySQL($date)
    {
        $day = substr($date, 0, 2);
        $month = substr($date, 3, 2);
        $year = substr($date, 6, 4);
        $hour = substr($date, 11, 2);
        $minute = substr($date, 14, 2);
        $second = substr($date, 17, 2);
        $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
        $date = date('Y-m-d H:i:s', $timestamp);
        return new \DateTime($date);
    }

    public static function slugifyCity($str)
    {
        return str_replace(' ', '-', trim(strtolower($str)));
    }
}