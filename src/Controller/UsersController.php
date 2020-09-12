<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\SelectUsersType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class UsersController extends AbstractController
{
    /**
     * @Route("/admin/users", name="app_users")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function show(Request $request, PaginatorInterface $paginator) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $repository = $entityManager->getRepository(User::class);
        $users = $repository->findAll();

        $paginationUsers = $paginator->paginate(
            $users,
            $request->query->getInt('page', 1),
            10
        );
        $form = $this->createForm(SelectUsersType::class, null, [
            'users' => $paginationUsers,
        ]);

        return $this->render('users/users_panel.html.twig', [
            'users' => $paginationUsers,
            'selectForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/users/block", name="app_block")
     * @param Request $request
     * @return Response
     */
    public function block(Request $request) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(SelectUsersType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usersToBeBlocked = $form->getData()['selectedUsers'];
            foreach ($usersToBeBlocked as $id) {
                $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
                $user->setIsBlocked(true);
            }
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_users');
    }

    /**
     * @Route("/admin/users/unblock", name="app_unblock")
     * @param Request $request
     * @return Response
     */
    public function unblock(Request $request) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(SelectUsersType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usersToBeUnblocked = $form->getData()['selectedUsers'];
            foreach ($usersToBeUnblocked as $id) {
                $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
                $user->setIsBlocked(false);
            }
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_users');
    }

    /**
     * @Route("/admin/users/delete", name="app_delete")
     * @param Request $request
     * @return Response
     */
    public function delete(Request $request) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(SelectUsersType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usersToBeDeleted = $form->getData()['selectedUsers'];
            foreach ($usersToBeDeleted as $id) {
                $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
                $entityManager->remove($user);
            }
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_users');
    }

    /**
     * @Route("/admin/users/activate", name="app_activate")
     * @param Request $request
     * @return Response
     */
    public function activate(Request $request) : Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $form = $this->createForm(SelectUsersType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usersToBeActivated = $form->getData()['selectedUsers'];
            foreach ($usersToBeActivated as $id) {
                $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
                $user->setIsVerified(true);
            }
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_users');
    }
}
