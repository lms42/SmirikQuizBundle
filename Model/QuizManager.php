<?php

namespace Smirik\QuizBundle\Model;

use Smirik\QuizBundle\Model\om\BaseUserQuiz;

class QuizManager
{
	
	/**
	 * Get $amount random questions related to quiz_id
	 * @param integer $quiz_id
	 * @param integer $amount
	 * @return array
	 */
	public function getRandomQuestions($quiz_id, $amount)
	{
		$num = QuestionQuery::create('q')
			->useQuizQuestionQuery()
				->filterByQuizId($quiz_id)
			->endUse()
			->groupBy('q.Id')
			->count();

		if ($num < $amount)
		{
			return false;
		}
		
		$rand  = array();
		$count = 0;
		while ($count < $amount)
		{
			$tmp = rand(0, $num-1);
			if (!in_array($tmp, $rand))
			{
				$rand[] = $tmp;
				$count++;
			}
		}

		$ids = array();
		foreach ($rand as $number)
		{
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
	 * Find or create user quiz related to parameters
	 * @param integer $user_id
	 * @param Smirik\QuizBundle\Model\Quiz $quiz
	 * @return Smirik\QuizBundle\Model\UserQuiz
	 */
	public function findOrCreateUserQuiz($user_id, $quiz)
	{
		$user_quiz = UserQuizQuery::create()
			->filterByUserId($user_id)
			->filterByQuizId($quiz->getId())
			->filterByIsActive(true)
			->filterByIsClosed(false)
			->findOne();

		if (!is_object($user_quiz))
		{
			$questions = $this->getRandomQuestions($quiz->getId(), $quiz->getNumQuestions());
			
			$user_quiz = new UserQuiz();
	    $user_quiz->setUserId($user_id);
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
	 * 
	 */
	public function getQuizesForUser($user_id)
	{
		$user_quiz = UserQuizQuery::create('uq')
			->filterByUserId($user_id)
			->joinWith('uq.Quiz')
			->find();
		return $user_quiz;
	}
	
}