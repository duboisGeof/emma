<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmmaAssure
 *
 * @ORM\Table(name="EMMA_ASSURE")
 * @ORM\Entity
 */
class EmmaAssure
{
	
	
    /**
     * @var string
     *
     * @ORM\Column(name="NIR", type="string", length=13, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="EMMA_ASSURE_NIR_seq", allocationSize=1, initialValue=1)
     */
    private $nir;

    /**
     * @var string
     *
     * @ORM\Column(name="CLE", type="string", length=2, nullable=true)
     */
    private $cle;

    /**
     * @var string
     *
     * @ORM\Column(name="CIV", type="string", length=5, nullable=true)
     */
    private $civ;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=30, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="PRENOM", type="string", length=20, nullable=true)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="ADRESSE", type="string", length=50, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="CPL_ADRESSE", type="string", length=20, nullable=true)
     */
    private $cplAdresse;

    /**
     * @var string
     *
     * @ORM\Column(name="CP", type="string", length=5, nullable=true)
     */
    private $cp;

    /**
     * @var string
     *
     * @ORM\Column(name="COMMUNE", type="string", length=50, nullable=true)
     */
    private $commune;

    /**
     * @var \Date
     *
     * @ORM\Column(name="DATE_INTEGR", type="date", nullable=true)
     */
    private $dateIntegr;

    /**
     * @var string
     *
     * @ORM\Column(name="NAT_ASSURE", type="string", length=5, nullable=true)
     */
    private $natAssure;
	
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
     * @return EmmaAssure
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
     * @return EmmaAssure
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
     * @return EmmaAssure
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
     * @return EmmaAssure
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
     * @return EmmaAssure
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
     * @return EmmaAssure
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
     * @return EmmaAssure
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
     * @return EmmaAssure
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
     * @return EmmaAssure
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
     * @return EmmaAssure
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
