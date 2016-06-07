<?php

namespace Geolocation\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Site
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Geolocation\AdminBundle\Repository\SiteRepository")
 */
class Site {

    private $iso;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Geolocation\AdminBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Geolocation\AdminBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $user;

    /**
     * @var \Geolocation\AdminBundle\Entity\Pilier
     *
     * @ORM\ManyToOne(targetEntity="Geolocation\AdminBundle\Entity\Pilier")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="pilier", referencedColumnName="id")
     * })
     */
    private $pilier;

    /**
     * @var boolean
     *
     * @ORM\Column(name="main", type="boolean")
     */
    private $main;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=false)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="siret", type="string", length=255, nullable=true)
     */
    private $siret;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255, nullable=false)
     */
    private $ville;

    /**
     * @var integer
     *
     * @ORM\Column(name="code_postal", type="string", length=255, nullable=false)
     */
    private $codePostal;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=false)
     */
    private $tel;

    /**
     * @var float
     *
     * @ORM\Column(name="latitude", type="float")
     */
    private $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float")
     */
    private $longitude;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isPublic", type="boolean")
     */
    private $isPublic;

    
    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @return mixed
     */
    public function getIso()
    {
        return $this->iso;
    }

    /**
     * @param mixed $iso
     */
    public function setIso($iso)
    {
        $this->iso = $iso;
    }
    
    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set main
     *
     * @param boolean $main
     *
     * @return Adresse
     */
    public function setMain($main) {
        $this->main = $main;

        return $this;
    }

    /**
     * Get main
     *
     * @return boolean
     */
    public function getMain() {
        return $this->main;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Adresse
     */
    public function setAdresse($adresse) {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse() {
        return $this->adresse;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Adresse
     */
    public function setVille($ville) {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille() {
        return $this->ville;
    }

    /**
     * Set codePostal
     *
     * @param integer $codePostal
     *
     * @return Adresse
     */
    public function setCodePostal($codePostal) {
        $this->codePostal = $codePostal;

        return $this;
    }

    /**
     * Get codePostal
     *
     * @return integer
     */
    public function getCodePostal() {
        return $this->codePostal;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Adresse
     */
    public function setTel($tel) {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel() {
        return $this->tel;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Adresse
     */
    public function setLatitude($latitude) {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude() {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Adresse
     */
    public function setLongitude($longitude) {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get isPublic
     *
     * @return boolean
     */
    public function getIsPublic() {
        return $this->isPublic;
    }

    /**
     * Set isPublic
     *
     * @param boolean $isPublic
     *
     * @return Adresse
     */
    public function setIsPublic($isPublic) {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude() {
        return $this->longitude;
    }

    /**
     * Set user
     *
     * @param \Geolocation\AdminBundle\Entity\User $user
     *
     * @return Adresse
     */
    public function setUser(\Geolocation\AdminBundle\Entity\User $user = null) {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Geolocation\AdminBundle\Entity\User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * Set siret
     *
     * @param string $siret
     *
     * @return Adresse
     */
    public function setSiret($siret) {
        $this->siret = $siret;

        return $this;
    }

    /**
     * Get siret
     *
     * @return string
     */
    public function getSiret() {
        return $this->siret;
    }

    /**
     * Set pilier
     *
     * @param \Geolocation\AdminBundle\Entity\Pilier $pilier
     *
     * @return Adresse
     */
    public function setPilier(\Geolocation\AdminBundle\Entity\Pilier $pilier = null) {
        $this->pilier = $pilier;

        return $this;
    }

    /**
     * Get pilier
     *
     * @return \Geolocation\AdminBundle\Entity\Pilier
     */
    public function getPilier() {
        return $this->pilier;
    }


    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Adresse
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    public function __toString()
    {
        return $this->getNom();
    }
}
