<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/question/{slug}", name="question")
     */
    public function showQuestion($slug) : Response
    {
        return $this->render('question/show.html.twig', [
            'controller_name' => 'QuestionController',
        ]);
    }
}
