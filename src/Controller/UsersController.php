<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Form\SelectFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
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
        //$users = $repository->findAll();
        //dump($users);
       // $id = $request->query->get('u');
        //$users = $userRepository->findUserData($id);
        //dump($userRepository->findUserData());
        $paginationUsers = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        $form = $this->createForm(SelectFormType::class, null, [
            'users' => $paginationUsers,
        ]);
        return $this->render('users/users_panel.html.twig', [
            'users' => $paginationUsers,
            'selectForm' => $form->createView()
        ]);
    }
//        if ($form->isSubmitted() && $form->isValid()) {
//            return $this->forward('App\Controller\UsersController', [
//                'selectedUsers' => $form->get('selectedUsers')
//            ]);
//        }

    /**
     * @Route("/admin/users/block", name="app_block")
     * @param Request $request
     * @return Response
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
     * @param Request $request
     * @return Response
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
     * @param Request $request
     * @return Response
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
     * @param Request $request
     * @return Response
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
