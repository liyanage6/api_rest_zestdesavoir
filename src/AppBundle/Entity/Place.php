<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Place
 * @package AppBundle\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(name="places")
 */
class Place
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    protected $id;

    /**
     * @ORM\Column(type="string", unique=true)
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @ORM\Column(type="string", name="address")
     * @Assert\NotBlank()
     */
    public $address;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Price", mappedBy="place")
     */
    public $prices;

    /**
     * @var Theme
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Theme", mappedBy="place")
     */
    protected $themes;

    public function __construct ()
    {
        $this->prices = new ArrayCollection();
        $this->themes = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId ()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId ($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName ()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName ($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAddress ()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress ($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getPrices ()
    {
        return $this->prices;
    }

    /**
     * @param mixed $prices
     */
    public function setPrices ($prices)
    {
        $this->prices = $prices;
    }

    /**
     * @return Theme
     */
    public function getThemes ()
    {
        return $this->themes;
    }

    /**
     * @param Theme $themes
     */
    public function setThemes ($themes)
    {
        $this->themes = $themes;
    }

}