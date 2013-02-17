<?php

namespace Smirik\QuizBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Smirik\PropelAdminBundle\Controller\AdminAbstractController as AbstractController;

use Smirik\PropelAdminBundle\Column\Column;
use Smirik\PropelAdminBundle\Column\CollectionColumn;
use Smirik\PropelAdminBundle\Action\Action;
use Smirik\PropelAdminBundle\Action\ObjectAction;
use Smirik\PropelAdminBundle\Action\SingleAction;
use FOS\UserBundle\Propel\UserQuery;

use Smirik\QuizBundle\Model\QuizQuery;

class AdminQuizController extends AbstractController
{
	
	public $layout = 'SmirikAdminBundle::layout.html.twig';
	public $name   = 'quiz';

	public function setup()
	{
		$this->configure(array(
								     array('name' => 'id', 'label' => 'Id', 'type' => 'integer', 'options' => array(
												 'editable' => false,
												 'listable' => true,
												 'sortable' => true,
												 'filterable' => true)),
		                 array('name' => 'title', 'label' => 'Title', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'description', 'label' => 'Description', 'type' => 'text', 'options' => array(
											 'editable' => true,
											 'listable' => false,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'time', 'label' => 'Time', 'type' => 'string', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'num_questions', 'label' => 'Number of questions', 'type' => 'integer', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'is_active', 'label' => 'Active', 'type' => 'boolean', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true)),
										 array('name' => 'is_opened', 'label' => 'Opened', 'type' => 'boolean', 'options' => array(
											 'editable' => true,
											 'listable' => true,
											 'sortable' => true,
											 'filterable' => true))
		                 ),
		                 array(
											'assign' => new ObjectAction('Assign', 'assign', 'admin_quiz_assign', true, false),
											'new' => new SingleAction('Create', 'new', 'admin_quiz_new', true),
											'edit' => new ObjectAction('Edit', 'edit', 'admin_quiz_edit', true),
											'delete' => new ObjectAction('Delete', 'delete', 'admin_quiz_delete', true, true))
		                );
	}

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
	
	/**
	 * @Route("/admin/quiz/{id}/assign", name="admin_quiz_assign")
	 * @Template("SmirikQuizBundle:Admin/Quiz:assign.html.twig")
	 */
	public function assignAction($id)
	{
		$quiz = QuizQuery::create()->findPk($id);

		if ($this->getRequest()->isXmlHttpRequest())
		{
			$ids = $this->getRequest()->request->get('ids', false);
            $uqm = $this->get('user_quiz.manager');

            $users = UserQuery::create()->findPks($ids);
			foreach ($users as $user)
			{
                $user_quiz = $uqm->findOrCreate($user, $quiz);
			}
			return new Response('{}');
		}
		
		return array(
			'quiz' => $quiz,
			'users' => array(),
			'route' => $this->generateUrl('admin_quiz_assign', array('id' => $id)),
		);
	}
	
}

