<?php

namespace Smirik\QuizBundle\Form\Type\Base;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

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

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Smirik\QuizBundle\Model\Question',
        );
    }

    public function getName()
    {
        return 'Question';
    }

}

