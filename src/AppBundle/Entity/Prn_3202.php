<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prn_3202
 *
 * @ORM\Table(name="prn_3202")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Prn_3202Repository")
 */
class Prn_3202
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
		
 
    private $nir;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_deb_prn", type="datetime")
     */
    private $dateDebPrn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin_prn", type="datetime", nullable=true)
     */
    private $dateFinPrn;

    /**
     * @var string
     *
     * @ORM\Column(name="subro", type="string", length=1, nullable=true)
     */
    private $subro;

    /**
     * @var string
     *
     * @ORM\Column(name="flux", type="string", length=20, nullable=true)
     */
    private $flux;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_recup", type="datetime")
     */
    private $dateRecup;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_envoi", type="datetime", nullable=true)
     */
    private $dateEnvoi;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_integer", type="datetime")
     */
    private $dateInteger;


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
     * @return Prn_3202
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
     * Set dateDebPrn
     *
     * @param \DateTime $dateDebPrn
     *
     * @return Prn_3202
     */
    public function setDateDebPrn($dateDebPrn)
    {
        $this->dateDebPrn = $dateDebPrn;

        return $this;
    }

    /**
     * Get dateDebPrn
     *
     * @return \DateTime
     */
    public function getDateDebPrn()
    {
        return $this->dateDebPrn;
    }

    /**
     * Set dateFinPrn
     *
     * @param \DateTime $dateFinPrn
     *
     * @return Prn_3202
     */
    public function setDateFinPrn($dateFinPrn)
    {
        $this->dateFinPrn = $dateFinPrn;

        return $this;
    }

    /**
     * Get dateFinPrn
     *
     * @return \DateTime
     */
    public function getDateFinPrn()
    {
        return $this->dateFinPrn;
    }

    /**
     * Set subro
     *
     * @param string $subro
     *
     * @return Prn_3202
     */
    public function setSubro($subro)
    {
        $this->subro = $subro;

        return $this;
    }

    /**
     * Get subro
     *
     * @return string
     */
    public function getSubro()
    {
        return $this->subro;
    }

    /**
     * Set flux
     *
     * @param string $flux
     *
     * @return Prn_3202
     */
    public function setFlux($flux)
    {
        $this->flux = $flux;

        return $this;
    }

    /**
     * Get flux
     *
     * @return string
     */
    public function getFlux()
    {
        return $this->flux;
    }

    /**
     * Set dateRecup
     *
     * @param \DateTime $dateRecup
     *
     * @return Prn_3202
     */
    public function setDateRecup($dateRecup)
    {
        $this->dateRecup = $dateRecup;

        return $this;
    }

    /**
     * Get dateRecup
     *
     * @return \DateTime
     */
    public function getDateRecup()
    {
        return $this->dateRecup;
    }

    /**
     * Set dateEnvoi
     *
     * @param \DateTime $dateEnvoi
     *
     * @return Prn_3202
     */
    public function setDateEnvoi($dateEnvoi)
    {
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }

    /**
     * Get dateEnvoi
     *
     * @return \DateTime
     */
    public function getDateEnvoi()
    {
        return $this->dateEnvoi;
    }

    /**
     * Set dateInteger
     *
     * @param \DateTime $dateInteger
     *
     * @return Prn_3202
     */
    public function setDateInteger($dateInteger)
    {
        $this->dateInteger = $dateInteger;

        return $this;
    }

    /**
     * Get dateInteger
     *
     * @return \DateTime
     */
    public function getDateInteger()
    {
        return $this->dateInteger;
    }
}

