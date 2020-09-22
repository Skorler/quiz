<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\ProgressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProgressRepository::class)
 */
class Progress
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Quiz::class, inversedBy="progresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Quiz $quiz;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isCompleted;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $startDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $endDate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="progresses")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?User $user;

    /**
     * @ORM\OneToMany(targetEntity=UserAnswer::class, mappedBy="progress", orphanRemoval=true)
     */
    private Collection $userAnswers;

    /**
     * @ORM\OneToOne(targetEntity=Question::class, cascade={"persist", "remove"})
     */
    private ?Question $lastQuestion;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $questionNumber;

    public function __construct()
    {
        $this->userAnswers = new ArrayCollection();
        $this->startDate = new \DateTime();
        $this->isCompleted = false;
        $this->endDate = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): self
    {
        $this->quiz = $quiz;

        return $this;
    }

    public function getIsCompleted(): ?bool
    {
        return $this->isCompleted;
    }

    public function setIsCompleted(bool $isCompleted): self
    {
        $this->isCompleted = $isCompleted;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|UserAnswer[]
     */
    public function getUserAnswers(): Collection
    {
        return $this->userAnswers;
    }

    public function addUserAnswer(UserAnswer $userAnswer): self
    {
        if (!$this->userAnswers->contains($userAnswer)) {
            $this->userAnswers[] = $userAnswer;
            $userAnswer->setProgress($this);
        }

        return $this;
    }

    public function removeUserAnswer(UserAnswer $userAnswer): self
    {
        if ($this->userAnswers->contains($userAnswer)) {
            $this->userAnswers->removeElement($userAnswer);
            // set the owning side to null (unless already changed)
            if ($userAnswer->getProgress() === $this) {
                $userAnswer->setProgress(null);
            }
        }

        return $this;
    }

    public function getLastQuestion(): ?Question
    {
        return $this->lastQuestion;
    }

    public function setLastQuestion(?Question $lastQuestion): self
    {
        $this->lastQuestion = $lastQuestion;

        return $this;
    }

    public function getQuestionNumber(): ?int
    {
        return $this->questionNumber;
    }

    public function setQuestionNumber(int $questionNumber): self
    {
        $this->questionNumber = $questionNumber;

        return $this;
    }
}
