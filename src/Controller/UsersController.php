<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\SelectFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UsersController extends AbstractController
{
    /**
     * @Route("/admin/users", name="app_users")
     */
    public function show(Request $request) : Response
    {
        $repository = $this->getDoctrine()->getRepository(User::class);
        $users = $repository->findAll();

        $form = $this->createForm(SelectFormType::class, null, [
            'users' => $users,
        ]);
//        if ($form->isSubmitted() && $form->isValid()) {
//            return $this->forward('App\Controller\UsersController', [
//                'selectedUsers' => $form->get('selectedUsers')
//            ]);
//        }

        return $this->render('users/users_panel.html.twig', [
            'users'=> $users,
            'selectForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/users/block", name="app_block")
     */
    public function block(Request $request) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $temp = $request->get('select_form');
        $usersToBeBlocked = $temp["selectedUsers"];
        foreach ($usersToBeBlocked as $id) {
            $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
            $user->setIsBlocked(true);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_users');
    }

    /**
     * @Route("/admin/users/unblock", name="app_unblock")
     */
    public function unblock(Request $request) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $temp = $request->get('select_form');
        $usersToBeUnblocked = $temp["selectedUsers"];
        foreach ($usersToBeUnblocked as $id) {
            $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
            $user->setIsBlocked(false);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_users');
    }

    /**
     * @Route("/admin/users/delete", name="app_delete")
     */
    public function delete(Request $request) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $temp = $request->get('select_form');
        $usersToBeDeleted = $temp["selectedUsers"];
        foreach ($usersToBeDeleted as $id) {
            $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_users');
    }

    /**
     * @Route("/admin/users/activate", name="app_activate")
     */
    public function activate(Request $request) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $temp = $request->get('select_form');
        $usersToBeDeleted = $temp["selectedUsers"];
        foreach ($usersToBeDeleted as $id) {
            $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
            $user->setIsVerified(true);
            $entityManager->flush();
        }
        return $this->redirectToRoute('app_users');
    }
}
