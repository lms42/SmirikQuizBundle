<?php

namespace Smirik\QuizBundle\Model;

use Smirik\QuizBundle\Model\om\BaseQuestion;


/**
 * Skeleton subclass for representing a row from the 'questions' table.
 *
 * 
 *
 * You should add additional methods to this class to meet the
 * application requirements.  This class will only be generated as
 * long as it does not already exist in the output directory.
 *
 * @package    propel.generator.vendor.bundles.Smirik.QuizBundle.Model
 */
class Question extends BaseQuestion {

	public function getTest()
	{
		$quizes = QuizQuestionQuery::create()
			->filterByQuestionId($this->getId())
			->groupBy('QuizId')
			->join('Quiz')
			->find()
        ;
        return $quizes;
	}
	
	public function getQuiz()
	{
		$quiz = QuizQuery::create()
			->useQuizQuestionQuery()
				->filterByQuestionId($this->getId())
			->endUse()
			->groupBy('Id')
			->find();
		return $quiz;
	}
	
	public function isQuiz()
	{
		return true;
	}
	
	public function setQuiz($quiz)
	{
		$quiz_ids = array();
		foreach ($quiz as $item)
		{
			$quiz_ids[] = $item->getId();
		}
		$qqs = QuizQuestionQuery::create()
			->filterByQuestionId($this->getId())
			->find();
		$questions_ids = array();
		/**
		 * Delete not marked objects
		 */
		foreach ($qqs as $qq)
		{
			if (!in_array($qq->getQuizId(), $quiz_ids))
			{
				$qq->delete();
			} else
			{
				$questions_ids[] = $qq->getQuizId();
			}
		}
		foreach ($quiz_ids as $id)
		{
			if (!in_array($id, $questions_ids))
			{
				$qq = new QuizQuestion();
				$qq->setQuestionId($this->getId());
				$qq->setQuizId($id);
				$qq->save();
				unset($qq);
			}
		}
	}

} // Question
