<?php

namespace Geolocation\AdminBundle\Domain\Api;


use Geocoder\Provider\GoogleMaps;
use Geolocation\AdminBundle\Entity\Site;
use Geolocation\AdminBundle\Entity\User;
use Ivory\HttpAdapter\CurlHttpAdapter;

class ApiLib
{

    // Classe facilitant certains traitements

    public static function searchAdresse(User $user = null, Site $adresse = null)
    {
        // Fonction de geocoder permettant de récupérer la latitude et la longitude en fonction d'une adresse donnée
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
        // Regex pour vérif de code postal
        if (preg_match("/^[0-9]{5,5}$/", $cp)) {
            return true;
        } else {
            return false;
        }
    }

    public static function dateToMySQL($date)
    {
        // Fonction qui converti un string en date mysql
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
        // permettant de slugifier une ville pour que la recherche en autocomplétion soit plus efficace
        return str_replace(' ', '-', trim(strtolower($str)));
    }
}