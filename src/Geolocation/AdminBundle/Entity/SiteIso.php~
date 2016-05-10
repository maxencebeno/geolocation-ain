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
     * @var \Geolocation\AdminBundle\Entity\Iso
     *
     * @ORM\ManyToOne(targetEntity="Geolocation\AdminBundle\Entity\Iso")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="iso_id", referencedColumnName="id")
     * })
     */
    private $isoId;

    /**
     * @var \Geolocation\AdminBundle\Entity\Adresse
     *
     * @ORM\ManyToOne(targetEntity="Geolocation\AdminBundle\Entity\Adresse")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="adresse_id", referencedColumnName="id")
     * })
     */
    private $siteId;

    /**
     * @var string
     *
     * @ORM\Column(name="autre", type="string", length=255, nullable=true)
     */
    private $autre;

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
     * Set isoId
     *
     * @param \Geolocation\AdminBundle\Entity\Iso $isoId
     *
     * @return SiteIso
     */
    public function setIsoId(\Geolocation\AdminBundle\Entity\Iso $isoId = null)
    {
        $this->isoId = $isoId;

        return $this;
    }

    /**
     * Get isoId
     *
     * @return \Geolocation\AdminBundle\Entity\Iso
     */
    public function getIsoId()
    {
        return $this->isoId;
    }

    /**
     * Set siteId
     *
     * @param \Geolocation\AdminBundle\Entity\Adresse $siteId
     *
     * @return SiteIso
     */
    public function setSiteId(\Geolocation\AdminBundle\Entity\Adresse $siteId = null)
    {
        $this->siteId = $siteId;

        return $this;
    }

    /**
     * Get siteId
     *
     * @return \Geolocation\AdminBundle\Entity\Adresse
     */
    public function getSiteId()
    {
        return $this->siteId;
    }
}
