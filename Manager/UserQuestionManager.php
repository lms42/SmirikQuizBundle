<?php

namespace Smirik\QuizBundle\Manager;

use Smirik\QuizBundle\Model\UserQuestionQuery;
use Smirik\QuizBundle\Model\UserQuestion;

class UserQuestionManager
{

    /**
     * Save or replace answer $user to question $question on quiz $user_quiz
     * @param $user
     * @param \Smirik\QuizBundle\Model\UserQuiz $user_quiz
     * @param \Smirik\QuizBundle\Model\Question $question
     * @param \Smirik\QuizBundle\Model\Answer $answer
     * @param string $answer_text
     * @return object|\Smirik\QuizBundle\Model\UserQuestion
     */
    public function saveAnswer($user, $user_quiz, $question, $answer, $answer_text)
    {
        $user_question = UserQuestionQuery::create()
            ->current($user->getId(), $user_quiz->getId(), $question->getId())
            ->findOne();
        if (!is_object($user_question)) {
            $user_question = new UserQuestion();
            $user_question->setUserId($user->getId());
            $user_question->setQuizId($user_quiz->getQuiz()->getId());
            $user_question->setQuestionId($question->getId());
            $user_question->setUserQuizId($user_quiz->getId());
            $user_question->save();
        }
        $user_question->addAnswer($answer, $answer_text);
        return $user_question;
    }

}