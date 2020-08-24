<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\SelectFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/admin/users", name="app_users")
     */
    public function show() : Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();

        $form = $this->createForm(SelectFormType::class, null, [
            'users' => $users,
        ]);

        return $this->render('users/users_panel.html.twig', [
            'users'=> $users,
            'selectForm' => $form->createView()
        ]);
    }
}
