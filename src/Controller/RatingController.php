<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{
    /**
     * @Route("/rating", name="rating")
     */
    public function showRating() : Response
    {
        return $this->render('rating/showRating.html.twig', [
            'controller_name' => 'RatingController',
        ]);
    }
}
