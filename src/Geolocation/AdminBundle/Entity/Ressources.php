<?php

namespace Geolocation\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ressources
 *
 * @ORM\Table(name="ressources", indexes={@ORM\Index(name="IDX_6A2CD5C7A76ED395", columns={"user_id"}), @ORM\Index(name="IDX_6A2CD5C7413D8865", columns={"cpf_id"})})
 * @ORM\Entity
 */
class Ressources
{
    /**
     * @var boolean
     *
     * @ORM\Column(name="besoin", type="boolean", nullable=false)
     */
    private $besoin;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \Geolocation\AdminBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Geolocation\AdminBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $user;

    /**
     * @var \Geolocation\AdminBundle\Entity\Cpf
     *
     * @ORM\ManyToOne(targetEntity="Geolocation\AdminBundle\Entity\Cpf")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="cpf_id", referencedColumnName="id")
     * })
     */
    private $cpf;

    /**
     * @var \Geolocation\AdminBundle\Entity\Site
     *
     * @ORM\ManyToOne(targetEntity="Geolocation\AdminBundle\Entity\Site")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="site", referencedColumnName="id", onDelete="CASCADE")
     * })
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="remarque", type="text", nullable=true)
     */
    private $remarque;

    /**
     * @var string
     *
     * @ORM\Column(name="quantite", type="text", nullable=true)
     */
    private $quantite;

    /**
     * Set besoin
     *
     * @param boolean $besoin
     *
     * @return Ressources
     */
    public function setBesoin($besoin)
    {
        $this->besoin = $besoin;

        return $this;
    }

    /**
     * Get besoin
     *
     * @return boolean
     */
    public function getBesoin()
    {
        return $this->besoin;
    }

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
     * Set user
     *
     * @param \Geolocation\AdminBundle\Entity\User $user
     *
     * @return Ressources
     */
    public function setUser(\Geolocation\AdminBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Geolocation\AdminBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set cpf
     *
     * @param \Geolocation\AdminBundle\Entity\Cpf $cpf
     *
     * @return Ressources
     */
    public function setCpf(\Geolocation\AdminBundle\Entity\Cpf $cpf = null)
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Get cpf
     *
     * @return \Geolocation\AdminBundle\Entity\Cpf
     */
    public function getCpf()
    {
        return $this->cpf;
    }

    /**
     * Set site
     *
     * @param \Geolocation\AdminBundle\Entity\Site $site
     *
     * @return Ressources
     */
    public function setAdresseId(\Geolocation\AdminBundle\Entity\Site $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get adresseId
     *
     * @return \Geolocation\AdminBundle\Entity\Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Ressources
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set remarque
     *
     * @param string $remarque
     *
     * @return Ressources
     */
    public function setRemarque($remarque)
    {
        $this->remarque = $remarque;

        return $this;
    }

    /**
     * Get remarque
     *
     * @return string
     */
    public function getRemarque()
    {
        return $this->remarque;
    }

    /**
     * Set quantite
     *
     * @param string $quantite
     *
     * @return Ressources
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return string
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set site
     *
     * @param \Geolocation\AdminBundle\Entity\Site $site
     *
     * @return Ressources
     */
    public function setSite(\Geolocation\AdminBundle\Entity\Site $site = null)
    {
        $this->site = $site;

        return $this;
    }
}
