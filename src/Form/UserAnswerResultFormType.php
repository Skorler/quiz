<?php

namespace App\Form;

use App\Entity\Answer;
use App\Entity\UserAnswer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAnswerResultFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->answers = $options['answers'];
        $builder
            ->add('answer', CollectionType::class, [
                'entry_type' => AnswerType::class,
                'entry_options' => ['label' => false],
                'by_reference' => false,
                'label' => false
            ])
            ->add('isCorrect')
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
