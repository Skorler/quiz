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

class CreateQuestionController extends AbstractController
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
            'controller_name' => 'CreateQuestionController',
            'createQuestionForm' => $form->createView()
        ]);
    }
}
