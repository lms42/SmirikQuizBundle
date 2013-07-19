<?php

namespace Smirik\QuizBundle\Listener;

use Smirik\AdminBundle\Event\ConfigureMenuEvent;
use Smirik\QuizBundle\Model\UserQuizQuery;

class ConfigureMenuListener
{

    protected $security_context;
    protected $translator;

    public function __construct($security_context, $translator)
    {
        $this->security_context = $security_context;
        $this->translator = $translator;
    }

    /**
     * @param \Smirik\AdminBundle\Event\ConfigureMenuEvent $event
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();
        $menu->addChild('admin.quiz.menu');
        $menu['admin.quiz.menu']->addChild('admin.quiz.navigation.quizes', array('route' => 'admin_quiz_index'));
        $menu['admin.quiz.menu']->addChild('admin.quiz.navigation.questions', array('route' => 'admin_questions_index'));
    }

    public function onMainMenuConfigure(ConfigureMenuEvent $event)
    {
        $menu = $event->getMenu();

        $user = $this->security_context->getToken()->getUser();


        if ($this->security_context->isGranted('ROLE_USER')) {

            $menu->addChild('Quizes', array('route' => 'smirik_quiz_index'));

            $key = 'Results';

            if (!isset($menu[$key])) {
                $menu->addChild($key, array('route' => 'account_my'));
            }

            $menu[$key]->addChild('Quizes results', array('route' => 'quiz_results'));

            $active_quiz = UserQuizQuery::create()
                ->filterByUserId($user->getId())
                ->filterByIsActive(true)
                ->filterByIsClosed(false)
                ->findOne();
            if ($active_quiz && (is_object($active_quiz))) {
                $menu->addChild($this->translator->trans('Current quiz').' <span class="badge badge-important">1</span>', array('route' => 'smirik_quiz_go', 'routeParameters' => array('uq_id' => $active_quiz->getId(), 'number' => 0)));

            }

        }
    }

}