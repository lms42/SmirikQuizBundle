<?php

namespace Smirik\QuizBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

use Smirik\QuizBundle\Model\QuizQuery;
use Smirik\QuizBundle\Model\UserQuery;

use Smirik\QuizBundle\Controller\Base\AdminQuizController as BaseController;

class AdminQuizController extends BaseController
{

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

