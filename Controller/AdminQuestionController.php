<?php

namespace Smirik\QuizBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

use Smirik\PropelAdminBundle\Column\Column;
use Smirik\PropelAdminBundle\Column\CollectionColumn;
use Smirik\PropelAdminBundle\Action\Action;
use Smirik\PropelAdminBundle\Action\ObjectAction;
use Smirik\PropelAdminBundle\Action\SingleAction;

class AdminQuestionController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'questions';

	public function setup()
	{
		$this->configure(array(
								     array('name' => 'id', 'label' => 'Id', 'type' => 'integer', 'options' => array(
												 'editable' => false,
												 'listable' => true,
												 'sortable' => true,
												 'filterable' => true)),
							       array('name' => 'test', 'label' => 'Quiz', 'type' => 'text', 'options' => array(
											 'editable' => false,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
		                 array('name' => 'text', 'label' => 'Text', 'type' => 'text', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'type', 'label' => 'Type', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'file', 'label' => 'File', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => false,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'num_answers', 'label' => 'Number of answers', 'type' => 'integer', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true))
		                 ),
		                 array('new' => new SingleAction('New', 'new', 'admin_questions_new', true),
											'edit' => new ObjectAction('Edit', 'edit', 'admin_questions_edit', true),
											'delete' => new ObjectAction('Delete', 'delete', 'admin_questions_delete', true, true))
		                );
	}

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
			'columns' => $this->columns,
			'routes' => $this->routes,
		);
	}

}

