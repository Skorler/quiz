<?php

declare(strict_types=1);

namespace App\Service\Question;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;

class QuestionManager
{
    private EntityManagerInterface $entityManager;
    private QuestionRepository $questionRepository;

    public function __construct(EntityManagerInterface $entityManager, QuestionRepository $questionRepository)
    {
        $this->entityManager = $entityManager;
        $this->questionRepository = $questionRepository;
    }

    public function getAllQuestions() : array
    {
        return $this->questionRepository->findAll();
    }

    public function create(Question $question) : void
    {
        foreach ($question->getAnswers() as $answer) {
            $this->entityManager->persist($answer);
        }
        $this->entityManager->persist($question);
        $this->entityManager->flush();
    }
}