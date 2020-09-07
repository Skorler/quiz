<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateQuizController extends AbstractController
{
    /**
     * @Route("/admin/new_quiz", name="app_quiz_new")
     */
    public function show() : Response
    {
        return $this->render('quiz/control_panel.html.twig');
    }
}
