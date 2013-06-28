<?php

namespace Smirik\QuizBundle\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quiz')
            ->add('text')
            ->add('type')
            ->add('file')
            ->add('num_answers')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Smirik\QuizBundle\Model\Question'
            )
        );
    }

    public function getName()
    {
        return 'Question';
    }

}

