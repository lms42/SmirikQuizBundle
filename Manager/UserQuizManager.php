<?php

namespace Smirik\QuizBundle\Manager;

use Smirik\QuizBundle\Model\UserQuizQuery;
use Smirik\QuizBundle\Model\QuestionQuery;
use Smirik\QuizBundle\Model\UserQuiz;

class UserQuizManager
{

    /**
     * Return collection of active quiz related to the user
     * @param $user
     * @return PropelObjectCollection
     */
    public function active($user)
    {
        return
            UserQuizQuery::create()
                ->filterByUserId($user->getId())
                ->filterByIsActive(true)
                ->filterByIsClosed(false)
                ->find();
    }

    /**
     * @param $user
     * @return PropelObjectCollection
     */
    public function completed($user)
    {
        return
            UserQuizQuery::create()
                ->filterByUserId($user->getId())
                ->filterByIsClosed(true)
                ->orderByCreatedAt('desc')
                ->find();
    }

    /**
     * Find or create user quiz related to parameters
     * @param integer $user_id
     * @param \Smirik\QuizBundle\Model\Quiz $quiz
     * @return \Smirik\QuizBundle\Model\UserQuiz
     */
    public function findOrCreate($user, $quiz)
    {
        $user_quiz = UserQuizQuery::create()
            ->filterByUserId($user->getId())
            ->filterByQuizId($quiz->getId())
            ->filterByIsActive(true)
            ->filterByIsClosed(false)
            ->findOne();

        if (!is_object($user_quiz)) {
            $questions = $this->getRandomQuestions($quiz->getId(), $quiz->getNumQuestions());

            $user_quiz = new UserQuiz();
            $user_quiz->setUserId($user->getId());
            $user_quiz->setQuizId($quiz->getId());

            $user_quiz->setQuestions(json_encode($questions));

            $user_quiz->setCurrent(0);
            $user_quiz->setStartedAt(new \DateTime('now'));
            $user_quiz->setIsActive(true);
            $user_quiz->setIsClosed(false);

            $user_quiz->save();
        }
        return $user_quiz;
    }

    /**
     * Get $amount random questions to the $quiz_id
     * @param int $quiz_id
     * @param int $amount
     * @return array|bool
     */
    public function getRandomQuestions($quiz_id, $amount)
    {
        $num = QuestionQuery::create('q')
            ->useQuizQuestionQuery()
            ->filterByQuizId($quiz_id)
            ->endUse()
            ->groupBy('q.Id')
            ->count();

        if ($num < $amount) {
            return false;
        }

        $rand = array();
        $count = 0;
        while ($count < $amount) {
            $tmp = rand(0, $num - 1);
            if (!in_array($tmp, $rand)) {
                $rand[] = $tmp;
                $count++;
            }
        }

        $ids = array();
        foreach ($rand as $number) {
            $qid = QuestionQuery::create('q')
                ->select('Id')
                ->useQuizQuestionQuery()
                ->filterByQuizId($quiz_id)
                ->endUse()
                ->groupBy('q.Id')
                ->limit(1)
                ->offset($number)
                ->findOne();
            $ids[] = $qid;
        }
        return $ids;
    }

    /**
     * @param $user
     * @return PropelObjectCollection
     */
    public function get($user)
    {
        $user_quiz = UserQuizQuery::create('uq')
            ->filterByUserId($user->getId())
            ->joinWith('uq.Quiz')
            ->orderByCreatedAt('desc')
            ->find();
        return $user_quiz;
    }


}