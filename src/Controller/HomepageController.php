<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Quiz;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function show(Request $request, PaginatorInterface $paginator) : Response
    {
        $repository = $this->getDoctrine()->getRepository(Quiz::class);
        $quizes = $repository->findAll();

        $paginationQuizes = $paginator->paginate(
            $quizes,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('homepage/home.html.twig', [
            'quizes' => $paginationQuizes
        ]);
    }
}
