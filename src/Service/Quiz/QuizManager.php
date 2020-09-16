<?php

declare(strict_types=1);

namespace App\Service\Quiz;

use App\Entity\Quiz;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;

class QuizManager
{
    private EntityManagerInterface $entityManager;
    private QuizRepository $quizRepository;

    public function __construct(EntityManagerInterface $entityManager, QuizRepository $quizRepository)
    {
        $this->entityManager = $entityManager;
        $this->quizRepository = $quizRepository;
    }

    public function create(Quiz $quiz) : void
    {
        $quiz->setIsActive(true);
        $this->entityManager->persist($quiz);
        $this->entityManager->flush();
    }

    public function activate(int $slug) : void
    {
        $quiz = $this->quizRepository->find($slug);
        $quiz->Activate();
        $this->entityManager->flush();
    }

    public function deactivate(int $slug) : void
    {
        $quiz = $this->quizRepository->find($slug);
        $quiz->Deactivate();
        $this->entityManager->flush();
    }

    public function delete(int $slug) : void
    {
        $quiz = $this->quizRepository->find($slug);
        $this->entityManager->remove($quiz);
        $this->entityManager->flush();
    }

    public function findById(int $slug) : Quiz
    {
        return $this->quizRepository->find($slug);
    }
}