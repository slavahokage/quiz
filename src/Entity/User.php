<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @ORM\Column(type="string", length=191, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=191, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(max=4096)
     */
    private $plainPassword;

    /**
     * @ORM\Column(type="array")
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ResultOfQuiz", mappedBy="user", orphanRemoval=true)
     */
    private $resultOfQuizzes;


    public function __construct()
    {
        $this->roles = array('ROLE_USER');
        $this->resultOfQuizzes = new ArrayCollection();
    }


    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }

    /**
     * @return mixed
     */
    public function getisActive(): bool
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive): void
    {
        $this->isActive = $isActive;
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }


    public function getPassword(): string
    {
        return $this->password;
    }


    public function setPassword($password): void
    {
        $this->password = $password;
    }


    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }


    public function getUsername(): string
    {
        return $this->username;
    }


    public function setUsername($username): void
    {
        $this->username = $username;
    }


    public function eraseCredentials()
    {

    }


    public function serialize()
    {
        return serialize([$this->id, $this->username, $this->email, $this->password, $this->roles]);
    }

    public function unserialize($serialized)
    {
        list($this->id, $this->username, $this->email, $this->password, $this->roles) = unserialize($serialized, ['allowed_classes' => false]);
    }

    /**
     * @return Collection|ResultOfQuiz[]
     */
    public function getResultOfQuizzes(): Collection
    {
        return $this->resultOfQuizzes;
    }

    public function addResultOfQuiz(ResultOfQuiz $resultOfQuiz): self
    {
        if (!$this->resultOfQuizzes->contains($resultOfQuiz)) {
            $this->resultOfQuizzes[] = $resultOfQuiz;
            $resultOfQuiz->setUser($this);
        }

        return $this;
    }

    public function removeResultOfQuiz(ResultOfQuiz $resultOfQuiz): self
    {
        if ($this->resultOfQuizzes->contains($resultOfQuiz)) {
            $this->resultOfQuizzes->removeElement($resultOfQuiz);
            if ($resultOfQuiz->getUser() === $this) {
                $resultOfQuiz->setUser(null);
            }
        }

        return $this;
    }

}
