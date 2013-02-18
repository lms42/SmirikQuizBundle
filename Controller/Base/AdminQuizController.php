<?php

namespace Smirik\QuizBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

class AdminQuizController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'quiz';
	public $bundle = 'SmirikQuizBundle';

	public function getQuery()
	{
		return \Smirik\QuizBundle\Model\QuizQuery::create();
	}
	
	public function getForm()
	{
		return new \Smirik\QuizBundle\Form\Type\QuizType;
	}
	
	public function getObject()
	{
		return new \Smirik\QuizBundle\Model\Quiz;
	}

}

