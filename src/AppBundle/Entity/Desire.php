<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Desire
 *
 * @ORM\Table(name="desire")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DesireRepository")
 */
class Desire
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
     *
     * @ORM\ManyToOne(targetEntity="Advertisement", inversedBy="desires")
     * @ORM\JoinColumn(name="adv_id", referencedColumnName="id")
     */
    private $advert;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @return mixed
     */
    public function getAdvert()
    {
        return $this->advert;
    }

    /**
     * @param mixed $advert
     * @return Desire
     */
    public function setAdvert($advert)
    {
        $this->advert = $advert;
        return $this;
    }


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
     * Set description
     *
     * @param string $description
     *
     * @return Desire
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
}
