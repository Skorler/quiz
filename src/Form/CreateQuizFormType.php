<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Question;
use App\Entity\Quiz;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateQuizFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->questions = $options['questions'];
        $builder
            ->add('name')
            ->add('questions', EntityType::class, [
                'class' => Question::class,
                'expanded' => true,
                'multiple' => true,
                'choices' =>$this->questions,
                'by_reference' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
            'questions' => null
        ]);
    }
}
