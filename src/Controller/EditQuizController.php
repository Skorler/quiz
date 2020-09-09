<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EditQuizController extends AbstractController
{
    /**
     * @Route("/admin/edit_quiz", name="app_quiz_edit")
     */
    public function show() : Response
    {
        return $this->render('quiz/control_panel.html.twig');
    }
}
