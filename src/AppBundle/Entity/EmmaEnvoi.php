<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Datetime;

/**
 * EmmaEnvoi
 *
 * @ORM\Table(name="EMMA_ENVOI")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmmaEnvoiRepository")
 */
class EmmaEnvoi
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DATE_ENVOI", type="datetime", nullable=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $dateEnvoi;

    /**
     * @var string
     *
     * @ORM\Column(name="TYPE_ENVOI", type="string", length=8, nullable=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $typeEnvoi;

    /**
     * @var string
     *
     * @ORM\Column(name="APPLI", type="string", length=6, nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $appli;

    /**
     * @var integer
     *
     * @ORM\Column(name="NB", type="integer", nullable=false)
     */
    private $nb;

    /**
     * Set dateEnvoi
     *
     * @param \DateTime $dateEnvoi
     *
     * @return EmmaEnvoi
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
     * Set typeEnvoi
     *
     * @param string $typeEnvoi
     *
     * @return EmmaEnvoi
     */
    public function setTypeEnvoi($typeEnvoi)
    {
        $this->typeEnvoi = $typeEnvoi;

        return $this;
    }

    /**
     * Get typeEnvoi
     *
     * @return string
     */
    public function getTypeEnvoi()
    {
        return $this->typeEnvoi;
    }

    /**
     * Set appli
     *
     * @param string $appli
     *
     * @return EmmaEnvoi
     */
    public function setAppli($appli)
    {
        $this->appli = $appli;

        return $this;
    }

    /**
     * Get appli
     *
     * @return string
     */
    public function getAppli()
    {
        return $this->appli;
    }

    /**
     * Set nb
     *
     * @param integer $nb
     *
     * @return EmmaEnvoi
     */
    public function setNb($nb)
    {
        $this->nb = $nb;

        return $this;
    }

    /**
     * Get nb
     *
     * @return integer
     */
    public function getNb()
    {
        return $this->nb;
    }
}
