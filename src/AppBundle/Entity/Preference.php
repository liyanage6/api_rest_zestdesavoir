<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Preference
 *
 * @ORM\Table(name="preference")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PreferenceRepository")
 */
class Preference
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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Choice(choices={"art", "sport", "histoire", "architecture", "science-fiction"}, message="La selection de la preference n'est pas valide")
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="value", type="integer")
     * @Assert\NotBlank()
     * @Assert\GreaterThan(value="-1")
     * @Assert\LessThan(value="11")
     */
    private $value;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="preferences")
     */
    private $user;


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
     * Set name
     *
     * @param string $name
     *
     * @return Preference
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set value
     *
     * @param integer $value
     *
     * @return Preference
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return User
     */
    public function getUser ()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser ($user)
    {
        $this->user = $user;
    }

}
