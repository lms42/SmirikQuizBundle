<?php

namespace Smirik\QuizBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AnswerType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('file', 'file', array('required' => false))
            ->add('is_right');
    }

    public function getDefaultOptions(array $options)
    {
        return array('data_class' => 'Smirik\QuizBundle\Model\Answer');
    }

    public function getName()
    {
        return 'smirik_quizbundle_answertype';
    }
}
