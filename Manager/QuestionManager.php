<?php

namespace Smirik\QuizBundle\Manager;

use Smirik\QuizBundle\Model\QuestionQuery;
use Smirik\QuizBundle\Model\AnswerQuery;
use Smirik\QuizBundle\Model\UserQuestionQuery;

class QuestionManager
{
    public function getByIds($ids)
    {
        return QuestionQuery::create()->findPks($ids);
    }
    
    public function getUserQuestions($user_quiz)
    {
        return UserQuestionQuery::create()
            ->filterByUserQuizId($user_quiz->getId())
            ->find()
        ;
    }
    
    public function getRightAnswers($questions)
    {
        $answers = AnswerQuery::create()
            ->filterByQuestionId(array_values($questions->getPrimaryKeys()))
            ->filterByIsRight(0, \Criteria::NOT_EQUAL)
            ->find()
        ;
        
        $res = array();
        foreach ($answers as $answer)
        {
            if ($answer->getTitle() == '')
            {
                $res[$answer->getQuestionId()] = $answer->getIsRight();
            } else
            {
                $res[$answer->getQuestionId()] = $answer->getTitle();
            }
        }
        return $res;
    }
    
    public function getUserAnswers($user_questions)
    {
        $answers = array();
        foreach ($user_questions as $user_question)
        {
            if ($user_question->getAnswerText() == '')
            {
                $answer = AnswerQuery::create()->findPk($user_question->getAnswerId());
                $answers[$user_question->getQuestionId()] = $answer->getTitle();
            } else
            {
                $answers[$user_question->getQuestionId()] = $user_question->getAnswerText();
            }
        }
        return $answers;
    }
    
}