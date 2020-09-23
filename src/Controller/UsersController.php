<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\SelectUsersType;
use App\Service\Users\UsersManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class UsersController extends AbstractController
{
    private UsersManager $usersManager;

    public function __construct(UsersManager $usersManager)
    {
        $this->usersManager = $usersManager;
    }

    /**
     * @Route("/admin/users", name="app_users")
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @return Response
     */
    public function show(Request $request, PaginatorInterface $paginator)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $queryBuilder = $entityManager->getRepository(User::class)->createQueryBuilder('u');
        if ($request->query->getAlnum('filter')) {
            $queryBuilder
                ->where('u.id LIKE :id')
                ->setParameter('id', '%' . $request->query->getAlnum('filter') . '%');
        }
        $query = $queryBuilder->getQuery();

        $paginationUsers = $paginator->paginate(
            $query,
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
        $form = $this->createForm(SelectUsersType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usersToBeDeleted = $form->getData()['selectedUsers'];
            $this->usersManager->block($usersToBeDeleted);
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
        $form = $this->createForm(SelectUsersType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usersToBeDeleted = $form->getData()['selectedUsers'];
            $this->usersManager->unblock($usersToBeDeleted);
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
        $form = $this->createForm(SelectUsersType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usersToBeDeleted = $form->getData()['selectedUsers'];
            $this->usersManager->delete($usersToBeDeleted);
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
        $form = $this->createForm(SelectUsersType::class, null);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usersToBeDeleted = $form->getData()['selectedUsers'];
            $this->usersManager->activate($usersToBeDeleted);
        }

        return $this->redirectToRoute('app_users');
    }
}
