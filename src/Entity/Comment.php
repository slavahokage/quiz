<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"api"})
     *
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=191)
     * @Groups({"api"})
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     * @Groups({"api"})
     */
    private $source;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     * @Groups({"api"})
     */
    private $real_name;

    /**
     * @ORM\Column(type="string", length=191,nullable=true)
     * @Groups({"api"})
     */
    private $extension;

    /**
     * @ORM\Column(type="string", length=191, nullable=true)
     * @Groups({"api"})
     */
    private $mime;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"api"})
     */
    private $creator;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\QuizTable", inversedBy="comments")
     *
     */
    private $quiz;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Article", inversedBy="comments")
     *
     */
    private $article;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"api"})
     */
    private $dateOfCreation;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDateOfCreation()
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getRealName(): ?string
    {
        return $this->real_name;
    }

    public function setRealName(string $real_name): self
    {
        $this->real_name = $real_name;

        return $this;
    }

    public function getExtension(): ?string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): self
    {
        $this->extension = $extension;

        return $this;
    }

    public function getMime(): ?string
    {
        return $this->mime;
    }

    public function setMime(string $mime): self
    {
        $this->mime = $mime;

        return $this;
    }

    public function getCreator(): ?User
    {
        return $this->creator;
    }

    public function setCreator(?User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    public function getQuiz(): ?QuizTable
    {
        return $this->quiz;
    }

    public function setQuiz(?QuizTable $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getArticle(): ?Article
    {
        return $this->article;
    }

    public function setArticle(?Article $article): self
    {
        $this->article = $article;

        return $this;
    }
}
