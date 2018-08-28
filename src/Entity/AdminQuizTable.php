<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdminQuizTableRepository")
 */
class AdminQuizTable implements \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191, unique=true )
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $description;

    /**
     * @ORM\Column(type="integer")
     */
    private $currentQuestion;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AdminQuestion", inversedBy="quizTables",  cascade={"persist", "remove"})
     */
    private $Question;



    public function __construct()
    {
        $this->Question = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getCurrentQuestion(): ?int
    {
        return $this->currentQuestion;
    }

    /**
     * @param mixed $currentQuestion
     */
    public function setCurrentQuestion($currentQuestion): void
    {
        $this->currentQuestion = $currentQuestion;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }


    /**
     * @return Collection|AdminQuestion[]
     */
    public function getQuestion(): Collection
    {
        return $this->Question;
    }

    public function addQuestion(AdminQuestion $question): self
    {
        if (!$this->Question->contains($question)) {
            $this->Question[] = $question;
        }

        return $this;
    }

    public function removeQuestion(AdminQuestion $question): self
    {
        if ($this->Question->contains($question)) {
            $this->Question->removeElement($question);
        }

        return $this;
    }

    public function serialize()
    {
        return serialize([$this->id, $this->title, $this->description, $this->Question]);
    }

    public function unserialize($serialized)
    {
        list($this->id, $this->title, $this->description, $this->Question)= unserialize($serialized,['allowed_classes' => false]);
    }


}
