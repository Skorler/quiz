<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\CreateQuizFormType;
use App\Service\Quiz\QuizManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateQuizController extends AbstractController
{
    /**
     * @Route("/admin/new_quiz", name="app_quiz_new")
     */
    public function show(Request $request, QuizManager $quizManager) : Response
    {
        $repository = $this->getDoctrine()->getRepository(Question::class);
        $questions = $repository->findAll();

        $quiz = new Quiz();
        $form = $this->createForm(CreateQuizFormType::class, $quiz, [
            'questions' => $questions
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $quizManager->create($quiz);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('quiz/new_quiz.html.twig', [
            'createQuizForm' => $form->createView(),
            'questions' => $questions
        ]);
    }
}
