<?php

namespace Smirik\QuizBundle\Manager;

use Smirik\QuizBundle\Model\QuizQuery;

class QuizManager
{

    /**
     * List of open quiz
     * @return PropelObjectCollection
     */
    public function available()
    {
        return
            QuizQuery::create()
                ->filterByIsOpened(true)
                ->find()
            ;
    }


}