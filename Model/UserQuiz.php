<?php

namespace Smirik\QuizBundle\Model;

use Smirik\QuizBundle\Model\om\BaseUserQuiz;


/**
 * Skeleton subclass for representing a row from the 'users_quiz' table.
 *
 *
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.vendor.bundles.Smirik.QuizBundle.Model
 */
class UserQuiz extends BaseUserQuiz
{
    public function isActiveForUser($user)
    {
        if ($this->getUserId() != $user->getId()) {
            return false;
        }

        if (($this->getIsClosed()) || (!is_null($this->getStoppedAt()))) {
            return false;
        }

        if (!$this->getIsActive()) {
            return false;
        }

        $now = time();
        $quiz_time = $this->getQuiz()->getTime();
        if ($quiz_time > 0) {
            $diff = ($now - strtotime($this->getStartedAt('Y-m-d H:i:s')));
            if ($diff > $quiz_time) {
                return false;
            }
        }
        return true;
    }

    public function close()
    {
        if (!$this->getIsClosed()) {
            /**
             * Setup stop time and is_close
             */
            $this->setStoppedAt(new \DateTime('now'));
            $this->setIsClosed(true);
            /**
             * Calculating the number of right answers
             * Also close users_questions
             */
            $users_questions = $this->getUserQuestions();

            $num_right_answers = 0;
            foreach ($users_questions as $question) {
                $question->setIsClosed(true);
                if ($question->getIsRight()) {
                    $num_right_answers++;
                }
            }
            $this->setNumRightAnswers($num_right_answers);
            $this->save();
        }
    }

    /**
     * Return number of second left to the quiz
     *
     * @param none
     * @return integer|false
     */
    public function countTimeLeft()
    {
        if ($this->getQuiz()->getTime() == 0) {
            return false;
        }

        $now = new \DateTime('now');
        $now = $now->getTimeStamp();
        $started_at = strtotime($this->getStartedAt('Y-m-d H:i:s'));

        $diff = $now - $started_at;
        $left_sec = $this->getQuiz()->getTime() - $diff;

        if ($left_sec <= 0) {
            return array('min' => 0, 'sec' => 0);
        }

        $min = (int)($left_sec / 60);
        $sec = $left_sec % 60;

        return array('min' => $min, 'sec' => $sec);
    }

} // UserQuiz
