<?php

namespace Geolocation\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VilleFrance
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Geolocation\AdminBundle\Repository\VilleFranceRepository")
 */
class VilleFrance
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_departement", type="string", length=3)
     */
    private $villeDepartement;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_slug", type="string", length=255)
     */
    private $villeSlug;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_nom", type="string", length=45)
     */
    private $villeNom;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_nom_simple", type="string", length=45)
     */
    private $villeNomSimple;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_nom_reel", type="string", length=45)
     */
    private $villeNomReel;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_nom_soundex", type="string", length=20)
     */
    private $villeNomSoundex;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_nom_metaphone", type="string", length=22)
     */
    private $villeNomMetaphone;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_code_postal", type="string", length=255)
     */
    private $villeCodePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_commune", type="string", length=3)
     */
    private $villeCommune;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_code_commune", type="string", length=5)
     */
    private $villeCodeCommune;

    /**
     * @var integer
     *
     * @ORM\Column(name="ville_arrondissement", type="smallint")
     */
    private $villeArrondissement;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_canton", type="string", length=4)
     */
    private $villeCanton;

    /**
     * @var integer
     *
     * @ORM\Column(name="ville_amdi", type="smallint")
     */
    private $villeAmdi;

    /**
     * @var integer
     *
     * @ORM\Column(name="ville_population_2010", type="integer")
     */
    private $villePopulation2010;

    /**
     * @var integer
     *
     * @ORM\Column(name="ville_population_1999", type="integer")
     */
    private $villePopulation1999;

    /**
     * @var integer
     *
     * @ORM\Column(name="ville_population_2012", type="integer")
     */
    private $villePopulation2012;

    /**
     * @var integer
     *
     * @ORM\Column(name="ville_densite_2010", type="integer")
     */
    private $villeDensite2010;

    /**
     * @var float
     *
     * @ORM\Column(name="ville_surface", type="float")
     */
    private $villeSurface;

    /**
     * @var float
     *
     * @ORM\Column(name="ville_longitude_deg", type="float")
     */
    private $villeLongitudeDeg;

    /**
     * @var float
     *
     * @ORM\Column(name="ville_latitude_deg", type="float")
     */
    private $villeLatitudeDeg;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_longitude_grd", type="string", length=9)
     */
    private $villeLongitudeGrd;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_latitude_grd", type="string", length=8)
     */
    private $villeLatitudeGrd;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_longitude_dms", type="string", length=9)
     */
    private $villeLongitudeDms;

    /**
     * @var string
     *
     * @ORM\Column(name="ville_latitude_dms", type="string", length=8)
     */
    private $villeLatitudeDms;

    /**
     * @var integer
     *
     * @ORM\Column(name="ville_zmin", type="integer")
     */
    private $villeZmin;

    /**
     * @var integer
     *
     * @ORM\Column(name="ville_zmax", type="integer")
     */
    private $villeZmax;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set villeDepartement
     *
     * @param string $villeDepartement
     *
     * @return VilleFrance
     */
    public function setVilleDepartement($villeDepartement)
    {
        $this->villeDepartement = $villeDepartement;

        return $this;
    }

    /**
     * Get villeDepartement
     *
     * @return string
     */
    public function getVilleDepartement()
    {
        return $this->villeDepartement;
    }

    /**
     * Set villeSlug
     *
     * @param string $villeSlug
     *
     * @return VilleFrance
     */
    public function setVilleSlug($villeSlug)
    {
        $this->villeSlug = $villeSlug;

        return $this;
    }

    /**
     * Get villeSlug
     *
     * @return string
     */
    public function getVilleSlug()
    {
        return $this->villeSlug;
    }

    /**
     * Set villeNom
     *
     * @param string $villeNom
     *
     * @return VilleFrance
     */
    public function setVilleNom($villeNom)
    {
        $this->villeNom = $villeNom;

        return $this;
    }

    /**
     * Get villeNom
     *
     * @return string
     */
    public function getVilleNom()
    {
        return $this->villeNom;
    }

    /**
     * Set villeNomSimple
     *
     * @param string $villeNomSimple
     *
     * @return VilleFrance
     */
    public function setVilleNomSimple($villeNomSimple)
    {
        $this->villeNomSimple = $villeNomSimple;

        return $this;
    }

    /**
     * Get villeNomSimple
     *
     * @return string
     */
    public function getVilleNomSimple()
    {
        return $this->villeNomSimple;
    }

    /**
     * Set villeNomReel
     *
     * @param string $villeNomReel
     *
     * @return VilleFrance
     */
    public function setVilleNomReel($villeNomReel)
    {
        $this->villeNomReel = $villeNomReel;

        return $this;
    }

    /**
     * Get villeNomReel
     *
     * @return string
     */
    public function getVilleNomReel()
    {
        return $this->villeNomReel;
    }

    /**
     * Set villeNomSoundex
     *
     * @param string $villeNomSoundex
     *
     * @return VilleFrance
     */
    public function setVilleNomSoundex($villeNomSoundex)
    {
        $this->villeNomSoundex = $villeNomSoundex;

        return $this;
    }

    /**
     * Get villeNomSoundex
     *
     * @return string
     */
    public function getVilleNomSoundex()
    {
        return $this->villeNomSoundex;
    }

    /**
     * Set villeNomMetaphone
     *
     * @param string $villeNomMetaphone
     *
     * @return VilleFrance
     */
    public function setVilleNomMetaphone($villeNomMetaphone)
    {
        $this->villeNomMetaphone = $villeNomMetaphone;

        return $this;
    }

    /**
     * Get villeNomMetaphone
     *
     * @return string
     */
    public function getVilleNomMetaphone()
    {
        return $this->villeNomMetaphone;
    }

    /**
     * Set villeCodePostal
     *
     * @param string $villeCodePostal
     *
     * @return VilleFrance
     */
    public function setVilleCodePostal($villeCodePostal)
    {
        $this->villeCodePostal = $villeCodePostal;

        return $this;
    }

    /**
     * Get villeCodePostal
     *
     * @return string
     */
    public function getVilleCodePostal()
    {
        return $this->villeCodePostal;
    }

    /**
     * Set villeCommune
     *
     * @param string $villeCommune
     *
     * @return VilleFrance
     */
    public function setVilleCommune($villeCommune)
    {
        $this->villeCommune = $villeCommune;

        return $this;
    }

    /**
     * Get villeCommune
     *
     * @return string
     */
    public function getVilleCommune()
    {
        return $this->villeCommune;
    }

    /**
     * Set villeCodeCommune
     *
     * @param string $villeCodeCommune
     *
     * @return VilleFrance
     */
    public function setVilleCodeCommune($villeCodeCommune)
    {
        $this->villeCodeCommune = $villeCodeCommune;

        return $this;
    }

    /**
     * Get villeCodeCommune
     *
     * @return string
     */
    public function getVilleCodeCommune()
    {
        return $this->villeCodeCommune;
    }

    /**
     * Set villeArrondissement
     *
     * @param integer $villeArrondissement
     *
     * @return VilleFrance
     */
    public function setVilleArrondissement($villeArrondissement)
    {
        $this->villeArrondissement = $villeArrondissement;

        return $this;
    }

    /**
     * Get villeArrondissement
     *
     * @return integer
     */
    public function getVilleArrondissement()
    {
        return $this->villeArrondissement;
    }

    /**
     * Set villeCanton
     *
     * @param string $villeCanton
     *
     * @return VilleFrance
     */
    public function setVilleCanton($villeCanton)
    {
        $this->villeCanton = $villeCanton;

        return $this;
    }

    /**
     * Get villeCanton
     *
     * @return string
     */
    public function getVilleCanton()
    {
        return $this->villeCanton;
    }

    /**
     * Set villeAmdi
     *
     * @param integer $villeAmdi
     *
     * @return VilleFrance
     */
    public function setVilleAmdi($villeAmdi)
    {
        $this->villeAmdi = $villeAmdi;

        return $this;
    }

    /**
     * Get villeAmdi
     *
     * @return integer
     */
    public function getVilleAmdi()
    {
        return $this->villeAmdi;
    }

    /**
     * Set villePopulation2010
     *
     * @param integer $villePopulation2010
     *
     * @return VilleFrance
     */
    public function setVillePopulation2010($villePopulation2010)
    {
        $this->villePopulation2010 = $villePopulation2010;

        return $this;
    }

    /**
     * Get villePopulation2010
     *
     * @return integer
     */
    public function getVillePopulation2010()
    {
        return $this->villePopulation2010;
    }

    /**
     * Set villePopulation1999
     *
     * @param integer $villePopulation1999
     *
     * @return VilleFrance
     */
    public function setVillePopulation1999($villePopulation1999)
    {
        $this->villePopulation1999 = $villePopulation1999;

        return $this;
    }

    /**
     * Get villePopulation1999
     *
     * @return integer
     */
    public function getVillePopulation1999()
    {
        return $this->villePopulation1999;
    }

    /**
     * Set villePopulation2012
     *
     * @param integer $villePopulation2012
     *
     * @return VilleFrance
     */
    public function setVillePopulation2012($villePopulation2012)
    {
        $this->villePopulation2012 = $villePopulation2012;

        return $this;
    }

    /**
     * Get villePopulation2012
     *
     * @return integer
     */
    public function getVillePopulation2012()
    {
        return $this->villePopulation2012;
    }

    /**
     * Set villeDensite2010
     *
     * @param integer $villeDensite2010
     *
     * @return VilleFrance
     */
    public function setVilleDensite2010($villeDensite2010)
    {
        $this->villeDensite2010 = $villeDensite2010;

        return $this;
    }

    /**
     * Get villeDensite2010
     *
     * @return integer
     */
    public function getVilleDensite2010()
    {
        return $this->villeDensite2010;
    }

    /**
     * Set villeSurface
     *
     * @param float $villeSurface
     *
     * @return VilleFrance
     */
    public function setVilleSurface($villeSurface)
    {
        $this->villeSurface = $villeSurface;

        return $this;
    }

    /**
     * Get villeSurface
     *
     * @return float
     */
    public function getVilleSurface()
    {
        return $this->villeSurface;
    }

    /**
     * Set villeLongitudeDeg
     *
     * @param float $villeLongitudeDeg
     *
     * @return VilleFrance
     */
    public function setVilleLongitudeDeg($villeLongitudeDeg)
    {
        $this->villeLongitudeDeg = $villeLongitudeDeg;

        return $this;
    }

    /**
     * Get villeLongitudeDeg
     *
     * @return float
     */
    public function getVilleLongitudeDeg()
    {
        return $this->villeLongitudeDeg;
    }

    /**
     * Set villeLatitudeDeg
     *
     * @param float $villeLatitudeDeg
     *
     * @return VilleFrance
     */
    public function setVilleLatitudeDeg($villeLatitudeDeg)
    {
        $this->villeLatitudeDeg = $villeLatitudeDeg;

        return $this;
    }

    /**
     * Get villeLatitudeDeg
     *
     * @return float
     */
    public function getVilleLatitudeDeg()
    {
        return $this->villeLatitudeDeg;
    }

    /**
     * Set villeLongitudeGrd
     *
     * @param string $villeLongitudeGrd
     *
     * @return VilleFrance
     */
    public function setVilleLongitudeGrd($villeLongitudeGrd)
    {
        $this->villeLongitudeGrd = $villeLongitudeGrd;

        return $this;
    }

    /**
     * Get villeLongitudeGrd
     *
     * @return string
     */
    public function getVilleLongitudeGrd()
    {
        return $this->villeLongitudeGrd;
    }

    /**
     * Set villeLatitudeGrd
     *
     * @param string $villeLatitudeGrd
     *
     * @return VilleFrance
     */
    public function setVilleLatitudeGrd($villeLatitudeGrd)
    {
        $this->villeLatitudeGrd = $villeLatitudeGrd;

        return $this;
    }

    /**
     * Get villeLatitudeGrd
     *
     * @return string
     */
    public function getVilleLatitudeGrd()
    {
        return $this->villeLatitudeGrd;
    }

    /**
     * Set villeLongitudeDms
     *
     * @param string $villeLongitudeDms
     *
     * @return VilleFrance
     */
    public function setVilleLongitudeDms($villeLongitudeDms)
    {
        $this->villeLongitudeDms = $villeLongitudeDms;

        return $this;
    }

    /**
     * Get villeLongitudeDms
     *
     * @return string
     */
    public function getVilleLongitudeDms()
    {
        return $this->villeLongitudeDms;
    }

    /**
     * Set villeLatitudeDms
     *
     * @param string $villeLatitudeDms
     *
     * @return VilleFrance
     */
    public function setVilleLatitudeDms($villeLatitudeDms)
    {
        $this->villeLatitudeDms = $villeLatitudeDms;

        return $this;
    }

    /**
     * Get villeLatitudeDms
     *
     * @return string
     */
    public function getVilleLatitudeDms()
    {
        return $this->villeLatitudeDms;
    }

    /**
     * Set villeZmin
     *
     * @param integer $villeZmin
     *
     * @return VilleFrance
     */
    public function setVilleZmin($villeZmin)
    {
        $this->villeZmin = $villeZmin;

        return $this;
    }

    /**
     * Get villeZmin
     *
     * @return integer
     */
    public function getVilleZmin()
    {
        return $this->villeZmin;
    }

    /**
     * Set villeZmax
     *
     * @param integer $villeZmax
     *
     * @return VilleFrance
     */
    public function setVilleZmax($villeZmax)
    {
        $this->villeZmax = $villeZmax;

        return $this;
    }

    /**
     * Get villeZmax
     *
     * @return integer
     */
    public function getVilleZmax()
    {
        return $this->villeZmax;
    }
}
