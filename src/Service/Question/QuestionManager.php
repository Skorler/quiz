<?php

declare(strict_types=1);

namespace App\Service\Question;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

class QuestionManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllQuestions() : array
    {
        return $this->entityManager->getRepository(Question::class)->findAll();
    }

    public function create(Question $question)
    {
        foreach ($question->getAnswers() as $answer) {
            $this->entityManager->persist($answer);
        }
        $this->entityManager->persist($question);
        $this->entityManager->flush();
    }
}