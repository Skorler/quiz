<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\CreateQuestionFormType;
use App\Service\Question\QuestionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{

    /**
     * @Route("/admin/question/create", name="create_question")
     */
    public function showCreateQuestion(Request $request, QuestionManager $questionManager) : Response
    {
        $question = new Question();
        $form = $this->createForm(CreateQuestionFormType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionManager->create($question);

            return $this->redirectToRoute('create_question');
        }

        return $this->render('question/create_question.twig', [
            'createQuestionForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/question/edit/{questionId}", name="edit_question")
     */
    public function editQuestion(Request $request, QuestionManager $questionManager, int $questionId) : Response
    {
        $question = $questionManager->findById($questionId);
        $form = $this->createForm(CreateQuestionFormType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $questionManager->edit($question);

            return $this->redirectToRoute('create_question');
        }

        return $this->render('question/edit_question.html.twig', [
            'createQuestionForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/question/delete/{questionId}", name="delete_question")
     */
    public function deleteQuestion(Request $request, QuestionManager $questionManager, int $questionId) : Response
    {
        $questionManager->delete($questionId);

        return $this->redirectToRoute('quiz_create');
    }
}
