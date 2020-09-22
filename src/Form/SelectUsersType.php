<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SelectUsersType extends AbstractType
{
    /**
     * @var mixed
     */

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $this->users = $options['users'];
        $builder
            ->add('selectedUsers', EntityType::class, [
                'class' => User::class,
                'expanded' => true,
                'multiple' => true,
                'choices' =>$this->users
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'users' => null
        ]);
    }
}
