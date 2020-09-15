<?php

declare(strict_types=1);

namespace App\Service\Users;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class UsersManager
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    public function getAllUsers() : array
    {
        return $this->userRepository->findAll();
    }

    public function register(User $user, EmailVerifier $emailVerifier) : void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // generate a signed url and email it to the user
        $emailVerifier->sendEmailConfirmation(
            'app_verify_email',
            $user,
            (new TemplatedEmail())
                ->from(new Address('noreply.quizzz@gmail.com', 'Quizzz Mail Bot'))
                ->to($user->getEmail())
                ->subject('Please Confirm your Email')
                ->htmlTemplate('registration/confirmation_email.html.twig')
        );
    }

    public function block(ArrayCollection $ids) : void
    {
        foreach ($ids as $id) {
            $user = $this->userRepository->find($id);
            $user->Block();
        }
        $this->entityManager->flush();
    }

    public function unblock(ArrayCollection $ids) : void
    {
        foreach ($ids as $id) {
            $user = $this->userRepository->find($id);
            $user->Unblock();
        }
        $this->entityManager->flush();
    }

    public function delete(ArrayCollection $ids) : void
    {
        foreach ($ids as $id) {
            $user = $this->userRepository->find($id);
            $this->entityManager->remove($user);
        }
        $this->entityManager->flush();
    }

    public function activate(ArrayCollection $ids) : void
    {
        foreach ($ids as $id) {
            $user = $this->userRepository->find($id);
            $user->Activate();
        }
        $this->entityManager->flush();
    }
}