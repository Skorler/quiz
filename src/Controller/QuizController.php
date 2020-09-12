<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Progress;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\UserAnswer;
use App\Form\CreateQuizFormType;
use App\Form\UserAnswerFormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use function Symfony\Component\String\u;

class QuizController extends \Symfony\Bundle\FrameworkBundle\Controller\AbstractController
{
    /**
     * @Route("/admin/quiz/activate/{slug}", name="quiz_activate")
     * @param Request $request
     * @return Response
     */
    public function activate(Request $request, $slug) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Quiz::class);
        $quiz = $repository->findOneBy(['id' => $slug]);
        $quiz->setIsActive(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/admin/quiz/delete/{slug}", name="quiz_deactivate")
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request, $slug) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Quiz::class);
        $quiz = $repository->findOneBy(['id' => $slug]);
        $entityManager->remove($quiz);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/admin/quiz/deactivate/{slug}", name="quiz_deactivate")
     * @param Request $request
     * @return Response
     */
    public function deactivate(Request $request, $slug) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Quiz::class);
        $quiz = $repository->findOneBy(['id' => $slug]);
        $quiz->setIsActive(false);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/admin/quiz/edit/{slug}", name="quiz_edit")
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request, $slug) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $repository = $this->getDoctrine()->getRepository(Quiz::class);
        $quiz = $repository->findOneBy(['id' => $slug]);

        $repository = $this->getDoctrine()->getRepository(Question::class);
        $questions = $repository->findAll();

        $form = $this->createForm(CreateQuizFormType::class, $quiz, [
            'questions' => $questions
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('quiz/edit_quiz.html.twig', [
            'createQuizForm' => $form->createView(),
            'quiz' => $quiz,
            'questions' => $questions
        ]);
    }


    /**
     * @Route("/quiz/play/{id}/{slug}", name="quiz_play")
     * @param Request $request
     * @return Response
     */
    public function play(Request $request, $id, $slug) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Quiz::class);
        $quiz = $repository->findOneBy(['id' => $id]);

        $repository = $entityManager->getRepository(Question::class);
        $question = $repository->findOneBy(['quiz' => $quiz, 'id' => $slug]);

        $userAnswer = new UserAnswer();
        $form = $this->createForm(UserAnswerFormType::class, $userAnswer);

        $progress = new Progress();
        $progress->setStartDate(new \DateTime());

        return $this->render('quiz/play_quiz.html.twig', [
            'question' => $question,
            'quiz' => $quiz,
            'userAnswerForm' => $form->createView()
        ]);
    }
}
