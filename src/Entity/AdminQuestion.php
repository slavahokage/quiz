<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AdminQuestionRepository")
 */
class AdminQuestion
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
     * @ORM\OneToMany(targetEntity="App\Entity\AdminAnswer", mappedBy="question", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $answers;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\AdminQuizTable", mappedBy="Question", cascade={"persist", "remove"})
     */
    private $quizTables;



    public function __construct()
    {
        $this->answers = new ArrayCollection();
        $this->quizTables = new ArrayCollection();
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
     * @return Collection|AdminAnswer[]
     */
    public function getAnswers(): Collection
    {
        return $this->answers;
    }

    public function addAnswer(AdminAnswer $answer): self
    {
        if (!$this->answers->contains($answer)) {
            $this->answers[] = $answer;
            $answer->setQuestion($this);
        }

        return $this;
    }

    public function removeAnswer(AdminAnswer $answer): self
    {
        if ($this->answers->contains($answer)) {
            $this->answers->removeElement($answer);
            if ($answer->getQuestion() === $this) {
                $answer->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|AdminQuizTable[]
     */
    public function getQuizTables(): Collection
    {
        return $this->quizTables;
    }

    public function addQuizTable(AdminQuizTable $quizTable): self
    {
        if (!$this->quizTables->contains($quizTable)) {
            $this->quizTables[] = $quizTable;
            $quizTable->addQuestion($this);
        }

        return $this;
    }

    public function removeQuizTable(AdminQuizTable $quizTable): self
    {
        if ($this->quizTables->contains($quizTable)) {
            $this->quizTables->removeElement($quizTable);
            $quizTable->removeQuestion($this);
        }

        return $this;
    }
}