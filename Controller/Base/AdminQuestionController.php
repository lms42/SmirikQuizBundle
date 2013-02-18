<?php

namespace Smirik\QuizBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

class AdminQuestionController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'questions';
	public $bundle = 'SmirikQuizBundle';

	public function getQuery()
	{
		return \Smirik\QuizBundle\Model\QuestionQuery::create();
	}
	
	public function getForm()
	{
		return new \Smirik\QuizBundle\Form\Type\QuestionType;
	}
	
	public function getObject()
	{
		return new \Smirik\QuizBundle\Model\Question;
	}

}

