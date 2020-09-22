<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Progress;
use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\UserAnswer;
use App\Form\CreateQuizFormType;
use App\Form\UserAnswerFormType;
use App\Form\UserAnswerResultFormType;
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
    public function activate(Request $request, int $slug) : Response
    {
        $this->quizManager->activate($slug);

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/admin/quiz/deactivate/{slug}", name="quiz_deactivate")
     * @param Request $request
     * @return Response
     */
    public function deactivate(Request $request, int $slug) : Response
    {
        $this->quizManager->deactivate($slug);

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/admin/quiz/delete/{slug}", name="quiz_deactivate")
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request, int $slug) : Response
    {
        $this->quizManager->delete($slug);

        return $this->redirectToRoute('app_home');
    }

    /**
     * @Route("/admin/quiz/edit/{slug}", name="quiz_edit")
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request, int $slug, QuestionManager $questionManager) : Response
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
     * @Route("/quiz/{quizId}/play", name="quiz_play")
     * @param Request $request
     * @return Response
     */
    public function play(Request $request, int $quizId) : Response
    {
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(Quiz::class);
        $quiz = $repository->find($quizId);

        $repository = $entityManager->getRepository(Progress::class);
        $progress = $repository->findOneBy(['quiz' => $quiz, 'user' => $user]);

        if ($progress == null) {
            $progress = new Progress();
            $progress->setQuiz($quiz);
            $progress->setUser($user);
            $progress->setQuestionNumber(0);
            $progress->setLastQuestion($quiz->getQuestions()[$progress->getQuestionNumber()]);
            $progress->setIsCompleted(false);
        }

        if ($progress->getIsCompleted() == true) {

            return $this->redirectToRoute('quiz_top', [
                'quizId' => $quizId
            ]);
        }

        $repository = $entityManager->getRepository(Question::class);
        $question = $repository->findOneBy(['id' => $progress->getLastQuestion()]);
        if ($question == null) {
            $progress->setIsCompleted(true);
            $progress->setEndDate(new \DateTime());
            $entityManager->persist($progress);
            $entityManager->flush();

            return $this->redirectToRoute('quiz_top', [
                'quizId' => $quizId
            ]);
        }

        $repository = $entityManager->getRepository(Answer::class);
        $answers = $repository->findBy(['question' => $question]);

        $userAnswer = new UserAnswer();
        $form = $this->createForm(UserAnswerFormType::class, $userAnswer, [
            'answers' => $answers
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $userAnswer->setProgress($progress);
            $userAnswer->setQuestion($question);
            $userAnswer->setIsCorrect($userAnswer->getAnswer()->getIsCorrect());
            if ($userAnswer->getIsCorrect() == true) {
                $this->addFlash(
                    'notice',
                    'Your answer was correct!'
                );
            }
            else {
                $this->addFlash(
                    'notice',
                    'Your answer was wrong!'
                );
            }
            $entityManager->persist($userAnswer);
            $progress->setQuestionNumber($progress->getQuestionNumber()+1);
            $progress->setLastQuestion($quiz->getQuestions()[$progress->getQuestionNumber()]);
            $entityManager->persist($progress);
            $entityManager->flush();

            return $this->render('quiz/play_quiz.html.twig', [
                'question' => $question,
                'quiz' => $quiz,
                'answers' => $answers,
                'userAnswerForm' => $form->createView()
            ]);
        }

        return $this->render('quiz/play_quiz.html.twig', [
            'question' => $question,
            'quiz' => $quiz,
            'answers' => $answers,
            'userAnswerForm' => $form->createView()
        ]);
    }


    /**
     * @Route("/quiz/{quizId}/top", name="quiz_top")
     * @param Request $request
     * @return Response
     */
    public function top(Request $request, int $quizId)
    {
        $user = $this->getUser();

        $entityManager = $this->getDoctrine()->getManager();

        $repository = $entityManager->getRepository(Progress::class);
        $progresses = $repository->findBy(['quiz' => $quizId, 'isCompleted' => true]);



        return $this->render('rating/showRating.html.twig', [
            'progresses' => $progresses,
            'current_user' => $user
        ]);
    }
}
