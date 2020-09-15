<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Progress;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\UserAnswer;
use App\Form\CreateQuizFormType;
use App\Form\UserAnswerFormType;
use App\Service\Question\QuestionManager;
use App\Service\Quiz\QuizManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends AbstractController
{
    private QuizManager $quizManager;

    public function __construct(QuizManager $quizManager)
    {
        $this->quizManager = $quizManager;
    }

    /**
     * @Route("/admin/quiz/activate/{slug}", name="quiz_activate")
     * @param Request $request
     * @return Response
     */
    public function activate(Request $request, $slug) : Response
    {
        $this->quizManager->activate($slug);

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/admin/quiz/deactivate/{slug}", name="quiz_deactivate")
     * @param Request $request
     * @return Response
     */
    public function deactivate(Request $request, $slug) : Response
    {
        $this->quizManager->deactivate($slug);

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/admin/quiz/delete/{slug}", name="quiz_deactivate")
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request, $slug) : Response
    {
        $this->quizManager->delete($slug);

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/admin/quiz/edit/{slug}", name="quiz_edit")
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request, $slug, QuestionManager $questionManager) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $quiz = $this->quizManager->findById($slug);
        $questions = $questionManager->getAllQuestions();

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
     * @Route("/quiz/play/{quizId}", name="quiz_play")
     * @param Request $request
     * @return Response
     */
    public function play(Request $request, $quizId) : Response
    {
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Quiz::class);
        $quiz = $repository->findOneBy(['id' => $quizId]);

        if ($user->)

        $repository = $entityManager->getRepository(Question::class);
        $question = $repository->findOneBy(['quiz' => $quizId, 'id' => $slug]);

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
