<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserAnswerRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserAnswerRepository::class)
 */
class UserAnswer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @ORM\ManyToOne(targetEntity=Progress::class, inversedBy="userAnswers")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Progress $progress;

    /**
     * @ORM\OneToOne(targetEntity=Answer::class, cascade={"persist", "remove"})
     */
    private ?Answer $answer;

    /**
     * @ORM\Column(type="boolean")
     */
    private ?bool $isCorrect;

    /**
     * @ORM\ManyToOne(targetEntity=Question::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Question $question;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProgress(): ?Progress
    {
        return $this->progress;
    }

    public function setProgress(?Progress $progress): self
    {
        $this->progress = $progress;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getAnswer(): ?Answer
    {
        return $this->answer;
    }

    public function setAnswer(?Answer $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getIsCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): self
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }
}
