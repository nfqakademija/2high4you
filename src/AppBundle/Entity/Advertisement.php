<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Advertisement
 *
 * @ORM\Table(name="advertisement")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AdvertisementRepository")
 */
class Advertisement
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
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="adverts")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationDate", type="date")
     */
    private $creationDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creationTime", type="time")
     */
    private $creationTime;

    /**
     * @var string
     *
     * @ORM\Column(name="theme", type="string", length=255)
     */
    private $theme;

    /**
     * @ORM\OneToMany(targetEntity="Desire", mappedBy="advert")
     */
    private $desires;
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * Advertisement constructor.
     * @param $desires
     */
    public function __construct()
    {
        $this->desires = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getDesires()
    {
        return $this->desires;
    }

    /**
     * @param mixed $desires
     * @return Advertisement
     */
    public function setDesires($desires)
    {
        $this->desires = $desires;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Advertisement
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     * @return Advertisement
     */
    public function setUser($user)
    {
        $this->user = $user;
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
     * Set creationDate
     *
     * @param \DateTime $creationDate
     *
     * @return Advertisement
     */
    public function setCreationDate($creationDate)
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * Get creationDate
     *
     * @return \DateTime
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * Set creationTime
     *
     * @param \DateTime $creationTime
     *
     * @return Advertisement
     */
    public function setCreationTime($creationTime)
    {
        $this->creationTime = $creationTime;

        return $this;
    }

    /**
     * Get creationTime
     *
     * @return \DateTime
     */
    public function getCreationTime()
    {
        return $this->creationTime;
    }

    /**
     * Set theme
     *
     * @param string $theme
     *
     * @return Advertisement
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->theme;
    }

}
