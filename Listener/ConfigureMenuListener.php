<?php

namespace Smirik\QuizBundle\Listener;

use Smirik\AdminBundle\Event\ConfigureMenuEvent;

class ConfigureMenuListener
{
    
    protected $security_context;
    
    public function __construct($security_context)
    {
        $this->security_context = $security_context;
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
        $id = false;
        if ($this->security_context->isGranted('ROLE_USER'))
        {
            $id = $user->getId();
            $menu['My cabinet']->addChild('Quizes results', array('route' => 'quiz_results'));
        }
    }
    
}