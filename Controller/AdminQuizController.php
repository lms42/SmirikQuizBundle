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
    public $name = 'quiz';
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

    /**
     * @Route("/admin/quiz/{id}/assign", name="admin_quiz_assign")
     * @Template("SmirikQuizBundle:Admin/Quiz:assign.html.twig")
     */
    public function assignAction($id)
    {
        $this->setup();

        $quiz = QuizQuery::create()->findPk($id);

        if ($this->getRequest()->isXmlHttpRequest()) {
            $ids = $this->getRequest()->request->get('ids', false);
            $uqm = $this->get('user_quiz.manager');

            $users = UserQuery::create()->findPks($ids);
            foreach ($users as $user) {
                $user_quiz = $uqm->findOrCreate($user, $quiz);
            }

            return new Response('{}');
        }

        return array(
            'quiz'  => $quiz,
            'users' => array(),
            'route' => $this->generateUrl('admin_quiz_assign', array('id' => $id)),
        );
    }

}

