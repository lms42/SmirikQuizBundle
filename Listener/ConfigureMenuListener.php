<?php

namespace Smirik\QuizBundle\Listener;

use Smirik\AdminBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
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
}