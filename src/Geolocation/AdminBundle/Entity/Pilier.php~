<?php

namespace Geolocation\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Pilier
 *
 * @ORM\Table(name="pilier")
 * @ORM\Entity(repositoryClass="Geolocation\AdminBundle\Repository\PilierRepository")
 */
class Pilier
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="categorie", type="string", length=255)
     */
    private $categorie;

    /**
     * @var string
     *
     * @ORM\Column(name="url_picto", type="string", length=255)
     */
    private $urlPicto;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Pilier
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

    /**
     * Set categorie
     *
     * @param string $categorie
     *
     * @return Pilier
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return string
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    public function __toString()
    {
        return $this->getNom();
    }

    /**
     * Set urlPicto
     *
     * @param string $urlPicto
     *
     * @return Pilier
     */
    public function setUrlPicto($urlPicto)
    {
        $this->urlPicto = $urlPicto;

        return $this;
    }

    /**
     * Get urlPicto
     *
     * @return string
     */
    public function getUrlPicto()
    {
        return $this->urlPicto;
    }
}
