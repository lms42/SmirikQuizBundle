<?php

namespace Smirik\QuizBundle\Controller;

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

	/**
	 * @Template("SmirikQuizBundle:Admin/Question:edit.html.twig")
	 */
	public function editAction($id)
	{
		$this->setup();
		$this->generateRoutes();
		$this->object = $this->getQuery()->findPk($id);
		if (!$this->object)
		{
			throw $this->createNotFoundException('Not found');
		}
		
		$request = $this->getRequest();
		
		$form = $this->createForm($this->getForm(), $this->object);

		if ('POST' == $request->getMethod())
		{
			$form->bindRequest($request);
			if ($form->isValid())
			{
				$this->object->save();
				return $this->redirect($this->generateUrl($this->routes['index']));
			}
		}

		return array(
			'layout' => $this->layout,
			'object' => $this->object,
			'form'   => $form->createView(),
			'columns' => $this->grid->getColumns(),
			'routes' => $this->routes,
		);
	}

}

