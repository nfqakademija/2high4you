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
     * @var int
     *
     * @ORM\Column(name="advId", type="integer")
     * @ORM\ManyToOne(targetEntity="Advertisement", inversedBy="id")
     */
    private $advId;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * Desire constructor.
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
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
     * Set advId
     *
     * @param integer $advId
     *
     * @return Desire
     */
    public function setAdvId($advId)
    {
        $this->advId = $advId;

        return $this;
    }

    /**
     * Get advId
     *
     * @return int
     */
    public function getAdvId()
    {
        return $this->advId;
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

