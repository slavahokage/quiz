<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuizTableRepository")
 */
class QuizTable implements \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive;

    /**
     * @ORM\Column(type="boolean")
     */
    private $canLook = true;

    /**
     * @ORM\Column(type="string", length=191)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateOfCreation;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Question", inversedBy="quizTables",  cascade={"persist", "remove"})
     */
    private $Question;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ResultOfQuiz", mappedBy="quiz", orphanRemoval=true)
     */
    private $resultOfQuizzes;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="quiz")
     */
    private $comments;


    public function __construct()
    {
        $this->Question = new ArrayCollection();
        $this->resultOfQuizzes = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCanLook()
    {
        return $this->canLook;
    }

    /**
     * @param mixed $canLook
     */
    public function setCanLook($canLook): void
    {
        $this->canLook = $canLook;
    }

    /**
     * @return mixed
     */
    public function getDateOfCreation(): \DateTime
    {
        return $this->dateOfCreation;
    }

    /**
     * @param mixed $dateOfCreation
     */
    public function setDateOfCreation($dateOfCreation): void
    {
        $this->dateOfCreation = $dateOfCreation;
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
     * @return Collection|Question[]
     */
    public function getQuestion(): Collection
    {
        return $this->Question;
    }


    public function addQuestion(Question $question): self
    {
        if (!$this->Question->contains($question)) {
            $this->Question[] = $question;
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->Question->contains($question)) {
            $this->Question->removeElement($question);
        }

        return $this;
    }

    public function clearQuestion(): self
    {
        $this->Question->clear();

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
            $resultOfQuiz->setQuiz($this);
        }

        return $this;
    }

    public function removeResultOfQuiz(ResultOfQuiz $resultOfQuiz): self
    {
        if ($this->resultOfQuizzes->contains($resultOfQuiz)) {
            $this->resultOfQuizzes->removeElement($resultOfQuiz);
            if ($resultOfQuiz->getQuiz() === $this) {
                $resultOfQuiz->setQuiz(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setQuiz($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->contains($comment)) {
            $this->comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getQuiz() === $this) {
                $comment->setQuiz(null);
            }
        }

        return $this;
    }

}
