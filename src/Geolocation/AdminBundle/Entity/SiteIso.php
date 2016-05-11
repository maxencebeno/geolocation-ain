<?php

namespace Geolocation\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SiteIso
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Geolocation\AdminBundle\Repository\SiteIsoRepository")
 */
class SiteIso
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
     * @var integer
     *
     * @ORM\Column(name="iso_id", type="integer")
     */
    private $isoId;

    /**
     * @var integer
     *
     * @ORM\Column(name="site_id", type="integer")
     */
    private $siteId;

    /**
     * @var string
     *
     * @ORM\Column(name="autre", type="string", length=255, nullable=true)
     */
    private $autre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_certification", type="datetime", nullable=true)
     */
    private $date_certification;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="certifie", type="boolean")
     */
    private $certifie;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="en_cours_certification", type="boolean")
     */
    private $en_cours_certification;
    
    

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
     * Set isoId
     *
     * @param integer $isoId
     *
     * @return SiteIso
     */
    public function setIsoId($isoId)
    {
        $this->isoId = $isoId;

        return $this;
    }

    /**
     * Get isoId
     *
     * @return integer
     */
    public function getIsoId()
    {
        return $this->isoId;
    }

    /**
     * Set siteId
     *
     * @param integer $siteId
     *
     * @return SiteIso
     */
    public function setSiteId($siteId)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * Get siteId
     *
     * @return integer
     */
    public function getSiteId()
    {
        return $this->siteId;
    }

    /**
     * Set autre
     *
     * @param string $autre
     *
     * @return SiteIso
     */
    public function setAutre($autre)
    {
        $this->autre = $autre;

        return $this;
    }

    /**
     * Get autre
     *
     * @return string
     */
    public function getAutre()
    {
        return $this->autre;
    }

    /**
     * Set dateCertification
     *
     * @param \DateTime $dateCertification
     *
     * @return SiteIso
     */
    public function setDateCertification($dateCertification)
    {
        $this->date_certification = $dateCertification;

        return $this;
    }

    /**
     * Get dateCertification
     *
     * @return \DateTime
     */
    public function getDateCertification()
    {
        return $this->date_certification;
    }

    /**
     * Set certifie
     *
     * @param boolean $certifie
     *
     * @return SiteIso
     */
    public function setCertifie($certifie)
    {
        $this->certifie = $certifie;

        return $this;
    }

    /**
     * Get certifie
     *
     * @return boolean
     */
    public function getCertifie()
    {
        return $this->certifie;
    }

    /**
     * Set enCoursCertification
     *
     * @param boolean $enCoursCertification
     *
     * @return SiteIso
     */
    public function setEnCoursCertification($enCoursCertification)
    {
        $this->en_cours_certification = $enCoursCertification;

        return $this;
    }

    /**
     * Get enCoursCertification
     *
     * @return boolean
     */
    public function getEnCoursCertification()
    {
        return $this->en_cours_certification;
    }
}
