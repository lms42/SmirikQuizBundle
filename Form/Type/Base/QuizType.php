<?php

namespace Smirik\QuizBundle\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('time')
            ->add('num_questions')
            ->add('is_active')
            ->add('is_opened')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Smirik\QuizBundle\Model\Quiz'
            )
        );
    }

    public function getName()
    {
        return 'Quiz';
    }

}

