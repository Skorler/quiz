<?php

declare(strict_types=1);

namespace App\Service\Users;

use App\Entity\User;
use App\Security\EmailVerifier;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class UsersManager
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getAllUsers() : array
    {
        return $this->entityManager->getRepository(User::class)->findAll();
    }

    public function register(User $user, EmailVerifier $emailVerifier)
    {
        $user->setIsBlocked(false);
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

    public function block(ArrayCollection $ids)
    {
        foreach ($ids as $id) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
            $user->setIsBlocked(true);
        }
        $this->entityManager->flush();
    }

    public function unblock(ArrayCollection $ids)
    {
        foreach ($ids as $id) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
            $user->setIsBlocked(false);
        }
        $this->entityManager->flush();
    }

    public function delete(ArrayCollection $ids)
    {
        foreach ($ids as $id) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
            $this->entityManager->remove($user);
        }
        $this->entityManager->flush();
    }

    public function activate(ArrayCollection $ids)
    {
        foreach ($ids as $id) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id]);
            $user->setIsVerified(true);
        }
        $this->entityManager->flush();
    }
}