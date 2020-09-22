<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=QuizRepository::class)
 */
class Quiz
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isActive;

    /**
     * @ORM\ManyToMany(targetEntity=Question::class, mappedBy="quiz")
     */
    private Collection $questions;

    /**
     * @ORM\OneToMany(targetEntity=Progress::class, mappedBy="quiz", orphanRemoval=true)
     */
    private Collection $progresses;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->progresses = new ArrayCollection();
        if (empty($this->isActive)) {
            $this->isActive = true;
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function Activate() :self
    {
        $this->isActive = true;

        return $this;
    }

    public function Deactivate() :self
    {
        $this->isActive = false;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->addQuiz($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            $question->removeQuiz($this);
        }

        return $this;
    }

    /**
     * @return Collection|Progress[]
     */
    public function getProgresses(): Collection
    {
        return $this->progresses;
    }

    public function addProgress(Progress $progress): self
    {
        if (!$this->progresses->contains($progress)) {
            $this->progresses[] = $progress;
            $progress->setQuiz($this);
        }

        return $this;
    }

    public function removeProgress(Progress $progress): self
    {
        if ($this->progresses->contains($progress)) {
            $this->progresses->removeElement($progress);
            // set the owning side to null (unless already changed)
            if ($progress->getQuiz() === $this) {
                $progress->setQuiz(null);
            }
        }

        return $this;
    }
}
