<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Quiz;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function show() : Response
    {
        $repository = $this->getDoctrine()->getRepository(Quiz::class);
        $quizes = $repository->findAll();

        return $this->render('homepage/home.html.twig', [
            'quizes' => $quizes
        ]);
    }
}
