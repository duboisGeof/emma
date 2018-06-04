<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Prn3202
 *
 * @ORM\Table(name="PRN_3202")
 * @ORM\Entity
 */
class Prn3202
{
	
	 /**
     * @var \EmmaAssure
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="EmmaAssure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="NIR", referencedColumnName="NIR")
     * })
     */
    private $nir;
	
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_DEB_PRN", type="datetime", nullable=false)
     */
    private $dateDebPrn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_FIN_PRN", type="datetime", nullable=true)
     */
    private $dateFinPrn;

    /**
     * @var string
     *
     * @ORM\Column(name="SUBRO", type="string", length=1, nullable=true)
     */
    private $subro;

    /**
     * @var string
     *
     * @ORM\Column(name="FLUX", type="string", length=20, nullable=true)
     */
    private $flux;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_RECUP", type="datetime", nullable=false)
     */
    private $dateRecup;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_ENVOI", type="datetime", nullable=true)
     */
    private $dateEnvoi;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_INTEGER", type="datetime", nullable=false)
     */
    private $dateInteger;

   /**
     * Set nir
     *
     * @param \AppBundle\Entity\EmmaAssure $nir
     *
     * @return Prn3202
     */
    public function setNir(\AppBundle\Entity\EmmaAssure $nir)
    {
        $this->nir = $nir;

        return $this;
    }

    /**
     * Get nir
     *
     * @return \AppBundle\Entity\EmmaAssure
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
     * @return Prn3202
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
     * @return Prn3202
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
     * @return Prn3202
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
     * @return Prn3202
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
     * @return Prn3202
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
     * @return Prn3202
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
     * @return Prn3202
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

    /**
     * Set nir
     *
     * @param \AppBundle\Entity\EmmaAssure $nir
     *
     * @return Prn3202
     */
    public function setNir(\AppBundle\Entity\EmmaAssure $nir)
    {
        $this->nir = $nir;

        return $this;
    }

    /**
     * Get nir
     *
     * @return \AppBundle\Entity\EmmaAssure
     */
    public function getNir()
    {
        return $this->nir;
    }
}
