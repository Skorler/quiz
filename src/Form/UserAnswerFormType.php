<?php

namespace App\Form;

use App\Entity\Answer;
use App\Entity\UserAnswer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAnswerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->answers = $options['answers'];
        $builder
            ->add('answer', EntityType::class, [
                'class' => Answer::class,
                'expanded' => true,
                'multiple' => false,
                'choices' =>$this->answers
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserAnswer::class,
            'answers' => null
        ]);
    }
}
