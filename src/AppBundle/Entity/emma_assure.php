<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * emma_assure
 *
 * @ORM\Table(name="emma_assure")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\emma_assureRepository")
 */
class emma_assure
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
     * @ORM\Column(name="nir", type="string", length=13, unique=true)
     */
    private $nir;

    /**
     * @var string
     *
     * @ORM\Column(name="cle", type="string", length=2)
     */
    private $cle;

    /**
     * @var string
     *
     * @ORM\Column(name="civ", type="string", length=5)
     */
    private $civ;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=30)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=20)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=50)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="cpl_adresse", type="string", length=20)
     */
    private $cplAdresse;

    /**
     * @var string
     *
     * @ORM\Column(name="cp", type="string", length=5)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="commune", type="string", length=50)
     */
    private $commune;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_integr", type="datetime")
     */
    private $dateIntegr;

    /**
     * @var string
     *
     * @ORM\Column(name="nat_assure", type="string", length=5)
     */
    private $natAssure;


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
     * Set nir
     *
     * @param string $nir
     *
     * @return emma_assure
     */
    public function setNir($nir)
    {
        $this->nir = $nir;

        return $this;
    }

    /**
     * Get nir
     *
     * @return string
     */
    public function getNir()
    {
        return $this->nir;
    }

    /**
     * Set cle
     *
     * @param string $cle
     *
     * @return emma_assure
     */
    public function setCle($cle)
    {
        $this->cle = $cle;

        return $this;
    }

    /**
     * Get cle
     *
     * @return string
     */
    public function getCle()
    {
        return $this->cle;
    }

    /**
     * Set civ
     *
     * @param string $civ
     *
     * @return emma_assure
     */
    public function setCiv($civ)
    {
        $this->civ = $civ;

        return $this;
    }

    /**
     * Get civ
     *
     * @return string
     */
    public function getCiv()
    {
        return $this->civ;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return emma_assure
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
     * Set prenom
     *
     * @param string $prenom
     *
     * @return emma_assure
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return emma_assure
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set cplAdresse
     *
     * @param string $cplAdresse
     *
     * @return emma_assure
     */
    public function setCplAdresse($cplAdresse)
    {
        $this->cplAdresse = $cplAdresse;

        return $this;
    }

    /**
     * Get cplAdresse
     *
     * @return string
     */
    public function getCplAdresse()
    {
        return $this->cplAdresse;
    }

    /**
     * Set cp
     *
     * @param string $cp
     *
     * @return emma_assure
     */
    public function setCp($cp)
    {
        $this->cp = $cp;

        return $this;
    }

    /**
     * Get cp
     *
     * @return string
     */
    public function getCp()
    {
        return $this->cp;
    }

    /**
     * Set commune
     *
     * @param string $commune
     *
     * @return emma_assure
     */
    public function setCommune($commune)
    {
        $this->commune = $commune;

        return $this;
    }

    /**
     * Get commune
     *
     * @return string
     */
    public function getCommune()
    {
        return $this->commune;
    }

    /**
     * Set dateIntegr
     *
     * @param \DateTime $dateIntegr
     *
     * @return emma_assure
     */
    public function setDateIntegr($dateIntegr)
    {
        $this->dateIntegr = $dateIntegr;

        return $this;
    }

    /**
     * Get dateIntegr
     *
     * @return \DateTime
     */
    public function getDateIntegr()
    {
        return $this->dateIntegr;
    }

    /**
     * Set natAssure
     *
     * @param string $natAssure
     *
     * @return emma_assure
     */
    public function setNatAssure($natAssure)
    {
        $this->natAssure = $natAssure;

        return $this;
    }

    /**
     * Get natAssure
     *
     * @return string
     */
    public function getNatAssure()
    {
        return $this->natAssure;
    }
}

