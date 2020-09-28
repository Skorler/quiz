<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\CreateQuizFormType;
use App\Service\Quiz\QuizManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateQuizController extends AbstractController
{
    /**
     * @Route("/admin/new_quiz", name="quiz_create")
     */
    public function show(Request $request, QuizManager $quizManager, PaginatorInterface $paginator) : Response
    {
        $repository = $this->getDoctrine()->getRepository(Question::class);
        $questions = $repository->findAll();

        $paginationQuestions = $paginator->paginate(
            $questions,
            $request->query->getInt('page', 1),
            10
        );

        $quiz = new Quiz();
        $form = $this->createForm(CreateQuizFormType::class, $quiz, [
            'questions' => $paginationQuestions
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $quizManager->create($quiz);

            return $this->redirectToRoute('app_home');
        }

        return $this->render('quiz/create_quiz.html.twig', [
            'createQuizForm' => $form->createView(),
            'questions' => $paginationQuestions
        ]);
    }
}
