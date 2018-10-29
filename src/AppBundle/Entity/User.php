<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
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
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $lastname;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    private $email;

    /**
     * @var Preference
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Preference", mappedBy="user")
     */
    private $preferences;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @var string
     *
     * @Assert\NotBlank(groups={"New", "FullUpdate"})
     * @Assert\Length(
     *     min="4",
     *     max="50"
     * )
     */
    protected $plainPassword;


    public function __construct ()
    {
        $this->preferences = new ArrayCollection();
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
     * Set lastname
     *
     * @param string $lastname
     *
     * @return User
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return User
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * @return Preference
     */
    public function getPreferences ()
    {
        return $this->preferences;
    }

    /**
     * @param Preference $preferences
     */
    public function setPreferences ($preferences)
    {
        $this->preferences = $preferences;
    }

    /**
     * @return mixed
     */
    public function getPassword ()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword ($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword ()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword ($plainPassword)
    {
        $this->plainPassword = $plainPassword;
    }

    public function getRoles ()
    {
        return [];
    }

    public function getSalt ()
    {
        return null;
    }

    public function getUsername ()
    {
        return $this->email;
    }

    public function eraseCredentials ()
    {
        // Suppression des données sensibles
        $this->plainPassword = null;
    }


}
