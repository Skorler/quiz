<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditQuestionController extends AbstractController
{
    /**
     * @Route("/edit/question/{slug}", name="edit_question")
     */
    public function showEditQuestion($slug) : Response
    {
        return $this->render('edit_question/showEditQuestion.html.twig', [
            'controller_name' => 'EditQuestionController',
        ]);
    }
}
