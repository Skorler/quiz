<?php

declare(strict_types=1);

namespace App\Service\Quiz;


use App\Entity\Question;
use App\Entity\Quiz;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Integer;

class QuizManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function create(Quiz $quiz)
    {
        $quiz->setIsActive(true);
        $this->entityManager->persist($quiz);
        $this->entityManager->flush();
    }

    public function activate(Integer $slug)
    {
        $quiz = $this->entityManager->getRepository(Quiz::class)->findOneBy(['id' => $slug]);
        $quiz->setIsActive(true);
        $this->entityManager->flush();
    }

    public function deactivate(Integer $slug)
    {
        $quiz = $this->entityManager->getRepository(Quiz::class)->findOneBy(['id' => $slug]);
        $quiz->setIsActive(false);
        $this->entityManager->flush();
    }

    public function delete(Integer $slug)
    {
        $quiz = $this->entityManager->getRepository(Quiz::class)->findOneBy(['id' => $slug]);
        $this->entityManager->remove($quiz);
        $this->entityManager->flush();
    }

    public function findById(Integer $slug) : Quiz
    {
        return $this->entityManager->getRepository(Quiz::class)->findOneBy(['id' => $slug]);
    }
}