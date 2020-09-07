<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Question;
use App\Form\CreateQuestionFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateQuestionController extends AbstractController
{
    /**
     * @Route("/admin/question/create", name="create_question")
     */
    public function showEditQuestion(Request $request) : Response
    {
        $question = new Question();

        $form = $this->createForm(CreateQuestionFormType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            foreach ($question->getAnswers() as $answer) {
                $entityManager->persist($answer);
            }
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('create_question');
        }

        return $this->render('question/create_question.twig', [
            'controller_name' => 'CreateQuestionController',
            'createQuestionForm' => $form->createView()
        ]);
    }
}
