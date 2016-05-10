<?php

namespace Geolocation\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Cpf
 *
 * @ORM\Table(name="cpf", indexes={@ORM\Index(name="IDX_3E3E11F0D823E37A", columns={"section_id"}), @ORM\Index(name="IDX_3E3E11F07A45358C", columns={"groupe_id"}), @ORM\Index(name="IDX_3E3E11F08F5EA509", columns={"classe_id"}), @ORM\Index(name="IDX_3E3E11F041859289", columns={"division_id"}), @ORM\Index(name="IDX_3E3E11F0BCF5E72D", columns={"categorie_id"}), @ORM\Index(name="IDX_3E3E11F0A27126E0", columns={"souscategorie_id"})})
 * @ORM\Entity(repositoryClass="Geolocation\AdminBundle\Repository\CpfRepository")
 */
class Cpf
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var \Geolocation\AdminBundle\Entity\Categorie
     *
     * @ORM\ManyToOne(targetEntity="Geolocation\AdminBundle\Entity\Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     * })
     */
    private $categorie;

    /**
     * @var \Geolocation\AdminBundle\Entity\Section
     *
     * @ORM\ManyToOne(targetEntity="Geolocation\AdminBundle\Entity\Section")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="section_id", referencedColumnName="id")
     * })
     */
    private $section;

    /**
     * @var \Geolocation\AdminBundle\Entity\Classe
     *
     * @ORM\ManyToOne(targetEntity="Geolocation\AdminBundle\Entity\Classe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="classe_id", referencedColumnName="id")
     * })
     */
    private $classe;

    /**
     * @var \Geolocation\AdminBundle\Entity\Groupe
     *
     * @ORM\ManyToOne(targetEntity="Geolocation\AdminBundle\Entity\Groupe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="groupe_id", referencedColumnName="id")
     * })
     */
    private $groupe;

    /**
     * @var \Geolocation\AdminBundle\Entity\Division
     *
     * @ORM\ManyToOne(targetEntity="Geolocation\AdminBundle\Entity\Division")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="division_id", referencedColumnName="id")
     * })
     */
    private $division;

    /**
     * @var \Geolocation\AdminBundle\Entity\SousCategorie
     *
     * @ORM\ManyToOne(targetEntity="Geolocation\AdminBundle\Entity\SousCategorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="souscategorie_id", referencedColumnName="id")
     * })
     */
    private $souscategorie;



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
     * Set categorie
     *
     * @param \Geolocation\AdminBundle\Entity\Categorie $categorie
     *
     * @return Cpf
     */
    public function setCategorie(\Geolocation\AdminBundle\Entity\Categorie $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \Geolocation\AdminBundle\Entity\Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set section
     *
     * @param \Geolocation\AdminBundle\Entity\Section $section
     *
     * @return Cpf
     */
    public function setSection(\Geolocation\AdminBundle\Entity\Section $section = null)
    {
        $this->section = $section;

        return $this;
    }

    /**
     * Get section
     *
     * @return \Geolocation\AdminBundle\Entity\Section
     */
    public function getSection()
    {
        return $this->section;
    }

    /**
     * Set classe
     *
     * @param \Geolocation\AdminBundle\Entity\Classe $classe
     *
     * @return Cpf
     */
    public function setClasse(\Geolocation\AdminBundle\Entity\Classe $classe = null)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return \Geolocation\AdminBundle\Entity\Classe
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set groupe
     *
     * @param \Geolocation\AdminBundle\Entity\Groupe $groupe
     *
     * @return Cpf
     */
    public function setGroupe(\Geolocation\AdminBundle\Entity\Groupe $groupe = null)
    {
        $this->groupe = $groupe;

        return $this;
    }

    /**
     * Get groupe
     *
     * @return \Geolocation\AdminBundle\Entity\Groupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * Set division
     *
     * @param \Geolocation\AdminBundle\Entity\Division $division
     *
     * @return Cpf
     */
    public function setDivision(\Geolocation\AdminBundle\Entity\Division $division = null)
    {
        $this->division = $division;

        return $this;
    }

    /**
     * Get division
     *
     * @return \Geolocation\AdminBundle\Entity\Division
     */
    public function getDivision()
    {
        return $this->division;
    }

    /**
     * Set souscategorie
     *
     * @param \Geolocation\AdminBundle\Entity\SousCategorie $souscategorie
     *
     * @return Cpf
     */
    public function setSouscategorie(\Geolocation\AdminBundle\Entity\SousCategorie $souscategorie = null)
    {
        $this->souscategorie = $souscategorie;

        return $this;
    }

    /**
     * Get souscategorie
     *
     * @return \Geolocation\AdminBundle\Entity\SousCategorie
     */
    public function getSouscategorie()
    {
        return $this->souscategorie;
    }

    public function __toString()
    {
        return $this->getSection() . ' ' . $this->getDivision() . ' ' . $this->getGroupe();
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Cpf
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
}
